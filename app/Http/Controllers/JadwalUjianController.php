<?php

namespace App\Http\Controllers;

use App\Models\SesiUjian;
use App\Models\JadwalUjian;
use App\Models\PesertaUjian;
use App\Models\RuanganUjian;
use Illuminate\Http\Request;
use App\Models\PengaturanUjian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalUjianRequest;
use App\Http\Resources\JadwalUjianResource;

class JadwalUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = JadwalUjian::with(['beasiswa', 'sesiUjian', 'ruanganUjian.ruangan'])
            ->withCount('pesertaUjian')
            ->orderBy('sesi', 'asc')->orderBy('id', 'asc');

        if ($request->filled('beasiswa_id')) {
            $dataQuery->where('beasiswa_id', $request->beasiswa_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $dataQuery->where(function ($query) use ($search) {
                $query->where('sesi', 'like', "%{$search}%");
                $query->orWhere('tanggal', 'like', "%{$search}%");
                $query->orWhereHas('ruanganUjian.ruangan', function ($q) use ($search) {
                    $q->where('nama', "%{$search}%");
                });
            });
        }


        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        if ($limit > 0) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(function ($item) {
                return new JadwalUjianResource($item);
            });
            $data->setCollection($resourceCollection);
        } else
            $data = $dataQuery->get();

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JadwalUjianRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = JadwalUjian::create($request->validated());
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
            $dataQuery = JadwalUjian::where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new JadwalUjianResource($dataQuery),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JadwalUjianRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = JadwalUjian::where('id', $id)->firstOrFail();
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
            $data = JadwalUjian::where('id', $id)->firstOrFail();
            $data->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function hapusJadwalUjian(string $id)
    {
        try {
            DB::beginTransaction();
            JadwalUjian::where('beasiswa_id', $id)->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function generateJadwal($beasiswa_id)
    {
        try {
            DB::beginTransaction();
            $pengaturan = PengaturanUjian::where('beasiswa_id', $beasiswa_id)->firstOrFail();
            $sesi_list = SesiUjian::where('beasiswa_id', $beasiswa_id)
                ->orderBy('sesi', 'asc')
                ->get();
            $ruangan_list = RuanganUjian::with('ruangan')
                ->where('beasiswa_id', $beasiswa_id)
                ->orderBy('urut', 'asc')
                ->get();

            $periode = \Carbon\CarbonPeriod::create($pengaturan->tanggal_mulai, $pengaturan->tanggal_selesai);
            $sesi_ujian = 1;
            $data = [];
            foreach ($periode as $tanggal) {
                foreach ($sesi_list as $sesi) {
                    foreach ($ruangan_list as $ruangan_ujian) {
                        $data[] = JadwalUjian::updateOrCreate(
                            [
                                'beasiswa_id' => $beasiswa_id,
                                'sesi' => $sesi_ujian,
                            ],
                            [
                                'sesi_ujian_id' => $sesi->id,
                                'ruangan_ujian_id'  => $ruangan_ujian->id,
                                'tanggal'     => $tanggal->toDateString(),
                            ]
                        );
                        $sesi_ujian++;
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'data jadwal berhasil diolah', 'data' => $data], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function tambahJadwalUjian($beasiswa_id, $pendaftar_id)
    {
        try {
            // 1. Cek apakah peserta sudah punya jadwal ujian untuk beasiswa ini
            $sudahAda = PesertaUjian::where('pendaftar_id', $pendaftar_id)
                ->whereHas('jadwalUjian', function ($q) use ($beasiswa_id) {
                    $q->where('beasiswa_id', $beasiswa_id);
                })
                ->exists();

            if ($sudahAda) {
                return [
                    'status'  => false,
                    'code'    => 400,
                    'message' => 'Peserta ini sudah memiliki jadwal ujian untuk beasiswa tersebut',
                    'data'    => null,
                ];
            }

            DB::beginTransaction();

            // 2. Ambil semua jadwal_ujian di beasiswa ini, urutkan berdasarkan id jadwal
            $jadwals = JadwalUjian::with('ruanganUjian')
                ->where('beasiswa_id', $beasiswa_id)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($jadwals->isEmpty()) {
                DB::rollBack();
                return [
                    'status'  => false,
                    'code'    => 400,
                    'message' => 'Jadwal ujian belum digenerate pada seleksi ini',
                    'data'    => null,
                ];
            }

            // 3. Loop dari jadwal pertama, cari yang masih ada slot
            foreach ($jadwals as $jadwal) {
                $ruang = $jadwal->ruanganUjian;
                if (! $ruang) {
                    continue;
                }
                $kapasitas = (int) $ruang->jumlah_peserta;

                // hitung berapa peserta yang sudah terdaftar di jadwal ini
                $terisi = PesertaUjian::where('jadwal_ujian_id', $jadwal->id)->count();

                if ($terisi < $kapasitas) {
                    $pesertaUjian = PesertaUjian::create([
                        'pendaftar_id'    => $pendaftar_id,
                        'jadwal_ujian_id' => $jadwal->id,
                    ]);

                    DB::commit();

                    return [
                        'status'  => true,
                        'code'    => 201,
                        'message' => 'Peserta berhasil ditempatkan ke jadwal ujian.',
                        'data'    => $pesertaUjian,
                    ];
                }
            }

            // 4. Kalau semua jadwal penuh
            DB::rollBack();

            return [
                'status'  => false,
                'message' => 'Semua jadwal ujian untuk beasiswa ini sudah penuh.',
                'code'    => 400,
                'data'    => null,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'status'  => false,
                'code'    => 500,
                'message' => 'Gagal menambah peserta: ' . $e->getMessage(),
                'data'    => null,
            ];
        }
    }


    public function simpanPesertaUjian(Request $request)
    {
        $data = $this->tambahJadwalUjian($request->beasiswa_id, $request->pendaftar_id);
        $code = $data['code'];
        unset($data['code']);
        return response()->json($data, $code);
    }
}
