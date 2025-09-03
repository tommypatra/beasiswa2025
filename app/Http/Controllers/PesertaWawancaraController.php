<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Pendaftar;
use App\Models\Pewawancara;
use App\Models\Verifikator;
use App\Models\UploadSyarat;
use Illuminate\Http\Request;
use App\Models\PesertaWawancara;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PesertaWawancaraRequest;
use App\Http\Resources\PesertaWawancaraResource;
use App\Http\Requests\SimpanValidasiFinalRequest;
use App\Http\Requests\SimpanValidasiSyaratRequest;

class PesertaWawancaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function wawancara(Request $request)
    {
        // 1. Beasiswa user sebagai verifikator
        $beasiswaIds = Verifikator::where('user_id', auth()->id())->pluck('beasiswa_id');

        // 3. Query beasiswa yang user jadi verifikator
        $dataQuery = Beasiswa::with('user')
            ->withCount([
                // hitung total peserta wawancara yang sudah diverifikasi (hasil=1)
                'pendaftar as total_pendaftar' => function ($query) {
                    $query->whereHas('verifikatorPendaftar', function ($q) {
                        $q->where('hasil', 1);
                    });
                },
                // hitung peserta yang sudah registrasi wawancara
                'pendaftar as peserta_registrasi' => function ($query) {
                    $query->where('is_registrasi_wawancara', 1);
                },
                // hitung peserta valid (nilai tidak null)
                'pesertaWawancara as peserta_valid' => function ($query) {
                    $query->whereNotNull('nilai');
                }
            ])
            ->whereIn('id', $beasiswaIds)
            ->orderBy('id', 'asc');

        // 3. Paging
        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);

        // 4. Response json
        return response()->json([
            'status'  => true,
            'message' => 'Pengambilan data berhasil',
            'data'    => $data
        ]);
    }

    public function pewawancara(Request $request)
    {
        $dataQuery = Pewawancara::with([
            'beasiswa',
            'user',
        ])
            ->withCount([
                'pesertaWawancara as total_pendaftar' => function ($query) {
                    $query->whereHas('pendaftar.verifikatorPendaftar', function ($q) {
                        $q->where('hasil', 1);
                    });
                }
            ])
            ->withCount([
                'pesertaWawancara as peserta_registrasi' => function ($query) {
                    $query->whereHas('pendaftar', function ($q) {
                        $q->where('is_registrasi_wawancara', 1);
                    });
                }
            ])
            ->withCount([
                'pesertaWawancara as peserta_valid' => function ($query) {
                    $query->whereNotNull('nilai');
                }
            ])
            ->where('user_id', auth()->user()->id)
            ->orderBy('beasiswa_id', 'asc')
            ->orderBy('user_id', 'asc');

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    public function index(Request $request)
    {
        $dataQuery = Pendaftar::with([
            'beasiswa',
            'Kelulusan',
            'pesertaWawancara.pewawancara.user',
            'mahasiswa.user.identitas',
            'mahasiswa.programStudi.fakultas'
        ])
            ->orderBy('beasiswa_id', 'asc')
            ->orderBy('id', 'asc');

        // $dataQuery->where(function ($query) {
        //     $query->WhereHas('pesertaWawancara.pewawancara', function ($q) {
        //         $q->where('user_id', auth()->user()->id);
        //     });
        // });

        $dataQuery->where(function ($query) {
            $query->WhereHas('verifikatorPendaftar', function ($q) {
                $q->where('hasil', 1);
            });
        });

        // if ($request->filled('pewawancara')) {
        //     if ($request->pewawancara)
        //         $dataQuery->whereHas('PesertaWawancara');
        //     else
        //         $dataQuery->whereDoesntHave('PesertaWawancara');
        // }

        if ($request->filled('beasiswa_id')) {
            $dataQuery->where('beasiswa_id', $request->beasiswa_id);
        }

        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('mahasiswa.user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });

            if ($request->filled('cari_pewawancara'))
                $dataQuery->orWhere(function ($query) use ($request) {
                    $query->WhereHas('pesertaWawancara.pewawancara.user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
                });
        }

        if ($request->filled('show_all') && izinkanAkses('admin')) {
            //
        } else {
            $dataQuery->where(function ($query) {
                $query->whereHas('pesertaWawancara.pewawancara', function ($q) {
                    $q->where('user_id', auth()->id());
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
            return new PesertaWawancaraResource($item);
        });
        $data->setCollection($resourceCollection);

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
    public function store(PesertaWawancaraRequest $request)
    {
        try {
            DB::beginTransaction();

            $datasave = $request->validated();
            $pendaftarIds = $datasave['pendaftar_id']; // Ambil array pendaftar_id
            $bulkInsertData = [];
            foreach ($pendaftarIds as $pendaftarId) {
                $bulkInsertData[] = [
                    'pewawancara_id' => $datasave['pewawancara_id'],
                    'pendaftar_id' => $pendaftarId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            PesertaWawancara::insert($bulkInsertData);

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
            $dataQuery = PesertaWawancara::with(['pendaftar.mahasiswa.user.identitas', 'pendaftar.beasiswa'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $dataQuery,
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
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = PesertaWawancara::where('id', $id)->firstOrFail();
            $data_save = [
                'status' => $request->status,
            ];
            $data->update($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }



    public function registasiPeserta(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Pendaftar::where('id', $id)->firstOrFail();

            $data->update(['is_registrasi_wawancara' => $request->is_registrasi_wawancara]);
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
            $data = PesertaWawancara::where('id', $id)->firstOrFail();
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
