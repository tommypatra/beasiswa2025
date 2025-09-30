<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Pendaftar;
use App\Models\UploadSyarat;
use Illuminate\Http\Request;
use App\Models\PesertaWawancara;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VerifikatorPendaftar;
use App\Http\Requests\SimpanValidasiFinalRequest;
use App\Http\Requests\SimpanValidasiSyaratRequest;
use App\Http\Requests\VerifikatorPendaftarRequest;
use App\Http\Resources\VerifikatorPendaftarResource;

class VerifikatorPendaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Pendaftar::with(['verifikatorPendaftar', 'mahasiswa.user.identitas', 'mahasiswa.programStudi.fakultas'])->orderBy('beasiswa_id', 'asc')->orderBy('id', 'asc');

        if ($request->filled('verifikator')) {
            if ($request->verifikator)
                $dataQuery->whereHas('verifikatorPendaftar');
            else
                $dataQuery->whereDoesntHave('verifikatorPendaftar');
        }
        if ($request->filled('beasiswa_id')) {
            $dataQuery->where('beasiswa_id', $request->beasiswa_id);
        }

        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('mahasiswa.user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        if ($request->filled('prodi')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('mahasiswa', function ($q) use ($request) {
                    $q->where('program_studi_id', $request->prodi);
                });
            });
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new VerifikatorPendaftarResource($item);
        });
        $data->setCollection($resourceCollection);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    public function pesertaVerifikasi(Request $request)
    {
        $dataQuery = Beasiswa::with([
            'user',
        ])
            ->withCount('pendaftar')
            ->withCount([
                'pendaftar as pendaftar_finalisasi' => function ($query) {
                    $query->where('is_finalisasi', 1);
                },
                'pendaftar as pendaftar_batal' => function ($query) {
                    $query->where('is_batal', 1);
                }
            ])
            ->withCount('verifikatorPendaftar')
            ->withCount([
                'verifikatorPendaftar as verifikasi_ms' => function ($query) {
                    $query->where('hasil', 1);
                },
                'verifikatorPendaftar as verifikasi_tms' => function ($query) {
                    $query->where('hasil', '!=', 1);
                }
            ])
            ->orderBy('daftar_mulai', 'desc');

        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('id')) {
            $dataQuery->where('id', $request->id);
        }

        // Ambil limit dari request, jika tidak ada gunakan default dari .env
        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;

        // Paginate hasil query
        $data = $dataQuery->paginate($limit);

        // Format response JSON
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
    public function store(VerifikatorPendaftarRequest $request)
    {
        try {
            DB::beginTransaction();

            $datasave = $request->validated();
            $pendaftarIds = $datasave['pendaftar_id']; // Ambil array pendaftar_id
            $bulkInsertData = [];
            foreach ($pendaftarIds as $pendaftarId) {
                $bulkInsertData[] = [
                    'verifikator_id' => $datasave['verifikator_id'],
                    'pendaftar_id' => $pendaftarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            VerifikatorPendaftar::insert($bulkInsertData);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data baru berhasil dibuat',
                'data' => $bulkInsertData
            ], 201);
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
            $dataQuery = VerifikatorPendaftar::with(['beasiswa', 'user.identitas'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new VerifikatorPendaftarResource($dataQuery),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }


    public function simpanValidasiSyarat(SimpanValidasiSyaratRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = UploadSyarat::where('id', $id)->firstOrFail();
            $pendaftar_id = $data->pendaftar_id;
            $data->update($request->validated());

            $totalSkor = UploadSyarat::where('pendaftar_id', $pendaftar_id)->sum('verifikasi_berkas_skor');
            $verifikator = VerifikatorPendaftar::where('pendaftar_id', $pendaftar_id)->firstOrFail();
            $verifikator->update([
                'total_skor' => $totalSkor
            ]);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function simpanValidasiFinal(SimpanValidasiFinalRequest $request, string $id)
    {
        try {
            DB::beginTransaction();


            $data = VerifikatorPendaftar::where('id', $id)->firstOrFail();
            $data->update($request->validated());

            //untuk membagi verifikator
            $beasiswa = Pendaftar::with(['beasiswa'])->where('id', $data->pendaftar_id)->firstOrFail();
            $data_respon['verifikator'] = $data;
            $data_respon['pewawancara'] = null;
            if ($data->hasil == 1) {
                if ($beasiswa->beasiswa->ada_wawancara == 1) {
                    $peserta_wawancara = PesertaWawancara::where('pendaftar_id', $data->pendaftar_id)->first();

                    // cari pewawancara
                    $pewawancara_id = cariPewawancara($beasiswa->beasiswa->id);
                    if (!$peserta_wawancara && $pewawancara_id) {
                        $data_pewawancara = PesertaWawancara::firstOrCreate(
                            ["pendaftar_id"   => $data->pendaftar_id], //cari dulu pendaftar_id, jika ada maka batalkan
                            ["pewawancara_id" => $pewawancara_id]
                        );
                        $data_respon['pewawancara'] = $data_pewawancara;
                    }
                }
            } else {
                $peserta_wawancara = PesertaWawancara::where('pendaftar_id', $data->pendaftar_id)->firstOrFail();
                $peserta_wawancara->delete();
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data_respon], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VerifikatorPendaftarRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = VerifikatorPendaftar::where('id', $id)->firstOrFail();

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
            $data = VerifikatorPendaftar::where('id', $id)->firstOrFail();
            $data->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }
}
