<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laporan;
// use App\Models\Kegiatan;
use App\Models\Penerima;
use App\Models\SkPenerima;
use App\Models\SubKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\LaporanRequest;
use App\Http\Resources\LaporanResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateVerifikasiRequest;
use App\Http\Resources\LaporanMahasiswaResource;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Laporan::with([
            'penerima.user.mahasiswa.programStudi.fakultas',
            'penerima.user.identitas',
            'penerima.skPenerima',
            'subKegiatan.kegiatan',
        ])
            ->orderBy('id', 'asc');

        if ($request->filled('id')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->where('id', $request->id);
            });
        }

        if ($request->filled('penerima_id')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->where('penerima_id', $request->penerima_id);
            });
        }

        if ($request->filled('sub_kegiatan_id')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->where('sub_kegiatan_id', $request->sub_kegiatan_id);
            });
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;

        if ($limit == 0) {
            $data = $dataQuery->get();
            $data = LaporanResource::collection($data);
        } else {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(function ($item) {
                return new LaporanResource($item);
            });
            $data->setCollection($resourceCollection);
        }

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    public function laporanMahasiswa(Request $request, $kegiatan_id)
    {
        $user_id = auth()->user()->id;
        $dataQuery = SubKegiatan::with([
            'laporan' => function ($q) use ($user_id) {
                $q->whereHas('penerima', function ($q2) use ($user_id) {
                    $q2->where('user_id', $user_id);
                });
            },
            'kegiatan',
        ])->orderBy('urut', 'asc')->orderBy('id', 'asc');

        $dataQuery->where(function ($query) use ($kegiatan_id) {
            $query->where('kegiatan_id', $kegiatan_id);
        });

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new LaporanMahasiswaResource($item);
        });
        $data->setCollection($resourceCollection);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    public function detailLaporan(Request $request, string $skId)
    {
        /* =========================
     * 1. AMBIL SK + KEGIATAN
     * ========================= */
        $sk = SkPenerima::with([
            'monitoring.kegiatan.subKegiatan' => fn($q) => $q->orderBy('urut'),
        ])->findOrFail($skId);

        $kegiatanList = $sk->monitoring
            ? $sk->monitoring->kegiatan->sortBy('urut')->values()
            : collect();

        $subKegiatanIds = $kegiatanList
            ->flatMap(fn($k) => $k->subKegiatan)
            ->pluck('id');


        /* =========================
     * 2. QUERY PENERIMA (PAGINATION)
     * ========================= */
        $penerimasQuery = Penerima::with([
            'user.identitas',
            'user.mahasiswa.programStudi.fakultas',
        ])
            ->where('sk_penerima_id', $sk->id)
            ->orderBy(
                User::select('name')
                    ->whereColumn('users.id', 'penerimas.user_id')
            );

        if ($request->filled('penerima_id')) {
            $penerimasQuery->where('penerima_id', $request->penerima_id);
        }

        $limit = (int) $request->input('limit', env('DEFAULT_LIMIT', 30));

        if ($limit === 0) {
            $paginator = null;
            $penerimas = $penerimasQuery->get();
        } else {
            $paginator = $penerimasQuery
                ->paginate($limit)
                ->appends($request->except('page'));

            $penerimas = $paginator->getCollection();
        }


        /* =========================
     * 3. QUERY LAPORAN (SEKALI SAJA)
     * ========================= */
        $laporans = Laporan::query()
            ->whereIn('penerima_id', $penerimas->pluck('id'))
            ->when(
                $subKegiatanIds->isNotEmpty(),
                fn($q) => $q->whereIn('sub_kegiatan_id', $subKegiatanIds)
            )
            ->orderByDesc('created_at')
            ->get([
                'id',
                'penerima_id',
                'sub_kegiatan_id',
                'path_jenis',
                'path',
                'is_kirim',
                'verifikasi_hasil',
                'verifikasi_skor',
                'verifikasi_catatan',
                'created_at',
            ]);

        /* index laporan: penerima_id + sub_kegiatan_id */
        $lapIndex = $laporans->groupBy(fn($l) => $l->penerima_id . '-' . $l->sub_kegiatan_id);


        /* =========================
     * 4. MAPPING DATA (LURUS & JELAS)
     * ========================= */
        $mapped = $penerimas->map(function ($p) use ($kegiatanList, $lapIndex) {
            $u  = $p->user;
            $mh = $u?->mahasiswa;
            $ps = $mh?->programStudi;
            $fk = $ps?->fakultas;

            $kegiatan = [];

            foreach ($kegiatanList as $k) {
                $subs = [];

                foreach ($k->subKegiatan as $s) {
                    $key = $p->id . '-' . $s->id;
                    $laps = $lapIndex->get($key, collect());

                    $subs[] = [
                        'sub_kegiatan_id'   => $s->id,
                        'sub_kegiatan_nama' => $s->nama,
                        'laporans' => $laps->map(fn($lap) => [
                            'laporan_id'         => $lap->id,
                            'path_jenis'         => $lap->path_jenis,
                            'path'               => $lap->path,
                            'is_kirim'           => $lap->is_kirim,
                            'verifikasi_hasil'   => $lap->verifikasi_hasil,
                            'verifikasi_skor'    => $lap->verifikasi_skor,
                            'verifikasi_catatan' => $lap->verifikasi_catatan,
                            'created_at'         => optional($lap->created_at)->toDateTimeString(),
                        ])->values(),
                    ];
                }

                $kegiatan[] = [
                    'kegiatan_id'   => $k->id,
                    'kegiatan_nama' => $k->nama,
                    'urut'          => $k->urut,
                    'sub_kegiatans' => $subs,
                ];
            }

            return [
                'penerima_id' => $p->id,
                'name'        => $u->name ?? '(tanpa nama)',
                'nim'         => $mh?->nim,
                'prodi'       => $ps?->nama,
                'fakultas'    => $fk?->nama,
                'foto'        => $u?->identitas?->foto ?? $u?->foto ?? '',
                'email'       => $u?->email,
                'no_hp'       => $u?->identitas?->no_hp ?? '',
                'kegiatan'    => $kegiatan,
            ];
        });


        /* =========================
     * 5. RESPONSE
     * ========================= */
        if ($limit === 0) {
            return response()->json([
                'status'  => true,
                'message' => 'ditemukan',
                'data'    => $mapped,
            ]);
        }

        $paginator->setCollection($mapped);

        return response()->json($paginator);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(LaporanRequest $request)
    {
        try {
            DB::beginTransaction();

            $data_save = $request->validated();
            $data_save['path'] = upload($request->file('path'), 'path');
            if (!$data_save['path']) {
                throw new \Exception('Gagal mengunggah file');
            }

            $data = Laporan::create($data_save);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $dataQuery = Laporan::where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new LaporanResource($dataQuery),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }


    public function finalisasiLaporan(string $id)
    {
        try {
            DB::beginTransaction();
            $data = Laporan::where('id', $id)->firstOrFail();
            $data->update(['is_kirim' => true]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LaporanRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Laporan::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateVerifikasi(UpdateVerifikasiRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Laporan::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $data = Laporan::where('id', $id)->firstOrFail();
            $path = $data->path;
            $data->delete();
            DB::commit();
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }
}
