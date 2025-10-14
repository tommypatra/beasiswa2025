<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Surveyor;
use App\Models\Pendaftar;
use App\Models\Pewawancara;
use App\Models\UploadSyarat;
use Illuminate\Http\Request;
use App\Models\PesertaSurvei;
use App\Models\SurveiPeserta;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PesertaSurveiRequest;
use App\Http\Resources\PesertaSurveiResource;
use App\Http\Resources\WilayahSurveiResource;
use App\Http\Requests\NilaiSurveiAkhirRequest;
use App\Http\Resources\ProgressSurveiResource;
use App\Http\Resources\VerifikasiBerkasResource;
use App\Http\Requests\SimpanValidasiFinalRequest;
use App\Http\Requests\SimpanValidasiSyaratRequest;

class PesertaSurveiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function progressSurvei(Request $request, $beasiswa_id)
    {

        $dataQuery = Surveyor::with([
            'surveiPeserta.pendaftar.mahasiswa.user.identitas',
            'beasiswa',
            'user',
        ])
            ->withCount([
                'surveipeserta as total_pendaftar' => function ($query) {
                    $query->whereHas('pendaftar.verifikatorPendaftar', function ($q) {
                        $q->where('hasil', 1);
                    });
                }
            ])
            ->withCount([
                'surveipeserta as peserta_valid' => function ($query) {
                    $query->whereNotNull('hasil');
                }
            ])->where(function ($query) use ($request) {
                $query->WhereHas('beasiswa', function ($q) use ($request) {
                    $q->where('is_aktif', 1);
                });
            })
            ->where('beasiswa_id', $beasiswa_id)
            ->having('total_pendaftar', '>', 0)
            ->orderBy('beasiswa_id', 'asc')
            ->orderBy('user_id', 'asc');


        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;

        if ($limit > 0) {
            // pakai pagination
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(fn($item) => new ProgressSurveiResource($item));
            $data->setCollection($resourceCollection);
        } else {
            // ambil semua data tanpa pagination
            $data = $dataQuery->get()->map(fn($item) => new ProgressSurveiResource($item));
        }

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    public function wilayahSurvei(Request $request, $beasiswa_id)
    {
        $dataQuery = Pendaftar::selectRaw('
            identitas.provinsi,
            identitas.kabupaten,
            identitas.kecamatan,
            COUNT(pendaftars.id) AS jumlah_pendaftar
        ')
            ->join('mahasiswas', 'mahasiswas.id', '=', 'pendaftars.mahasiswa_id')
            ->join('users', 'users.id', '=', 'mahasiswas.user_id')
            ->join('identitas', 'identitas.user_id', '=', 'users.id')
            ->where('pendaftars.beasiswa_id', $beasiswa_id)
            ->whereHas('verifikatorPendaftar', function ($q) {
                $q->where('hasil', 1);
            })
            ->groupBy('identitas.provinsi', 'identitas.kabupaten', 'identitas.kecamatan')
            ->orderBy('identitas.provinsi')
            ->orderBy('identitas.kabupaten')
            ->orderBy('identitas.kecamatan');

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? (int) $request->limit : $default_limit;

        if ($limit > 0) {
            // pakai pagination
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(fn($item) => new WilayahSurveiResource($item));
            $data->setCollection($resourceCollection);
        } else {
            // ambil semua data tanpa pagination
            $data = $dataQuery->get()->map(fn($item) => new WilayahSurveiResource($item));
        }

        return response()->json([
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ]);
    }

    public function survei(Request $request)
    {
        $dataQuery = Surveyor::with([
            'beasiswa',
            'user',
        ])
            ->withCount([
                'surveipeserta as total_pendaftar' => function ($query) {
                    $query->whereHas('pendaftar.verifikatorPendaftar', function ($q) {
                        $q->where('hasil', 1);
                    });
                }
            ])
            ->withCount([
                'surveipeserta as peserta_valid' => function ($query) {
                    $query->whereNotNull('hasil');
                }
            ])->where(function ($query) use ($request) {
                $query->WhereHas('beasiswa', function ($q) use ($request) {
                    $q->where('is_aktif', 1);
                });
            })
            ->where('user_id', auth()->user()->id)
            ->orderBy('beasiswa_id', 'asc')
            ->orderBy('user_id', 'asc');

        // if ($request->filled('grup')) {
        //     if ($request->filled('grup') == 'registrasi')
        //         $dataQuery->where(function ($query) use ($request) {
        //             $query->WhereHas('mahasiswa.user', function ($q) use ($request) {
        //                 $q->where('name', 'like', '%' . $request->search . '%');
        //             });
        //         });
        // }


        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;

        if ($limit > 0) {
            // pakai pagination
            $data = $dataQuery->paginate($limit);
        } else {
            // ambil semua data tanpa pagination
            $data = $dataQuery->get();
        }


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
            'surveiPeserta.surveyor.user',
            'mahasiswa.user.identitas',
            'mahasiswa.programStudi.fakultas'
        ])
            ->orderBy('beasiswa_id', 'asc')
            ->orderBy('id', 'asc');

        $dataQuery->where(function ($query) {
            $query->WhereHas('surveiPeserta.surveyor', function ($q) {
                $q->where('user_id', auth()->user()->id);
            });
        });

        $dataQuery->where(function ($query) {
            $query->WhereHas('verifikatorPendaftar', function ($q) {
                $q->where('hasil', 1);
            });
        });

        // if ($request->filled('pewawancara')) {
        //     if ($request->pewawancara)
        //         $dataQuery->whereHas('PesertaSurvei');
        //     else
        //         $dataQuery->whereDoesntHave('PesertaSurvei');
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
                    $query->WhereHas('PesertaSurvei.pewawancara.user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
                });
        }

        // if ($request->filled('is_pewawancara'))
        //     $dataQuery->orWhere(function ($query) use ($request) {
        //         $query->WhereHas('PesertaSurvei.pewawancara', function ($q) use ($request) {
        //             $q->where('user_id', auth()->user()->id);
        //         });
        //     });

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
            return new PesertaSurveiResource($item);
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
    public function store(Request $request)
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

            PesertaSurvei::insert($bulkInsertData);

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
            // $dataQuery = SurveiPeserta::with(['pendaftar.mahasiswa.user.identitas', 'pendaftar.beasiswa'])->where('id', $id)->firstOrFail();
            $dataQuery = Pendaftar::with([
                'surveiPeserta'
            ])->where('id', $id)->firstOrFail();
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
    public function update(NilaiSurveiAkhirRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();

            $data = SurveiPeserta::where('id', $id)->firstOrFail();

            //untuk cek sudah upload atau belum
            $controller = new DokumentasiSurveiController();
            $response = $controller->dataDokumentasiSurvei($data->pendaftar_id)->getData(); // JSON → object
            if (!$response->status) {
                return response()->json(['status' => false, 'message' => 'Sebelum mengakhiri wajib upload foto dokumentasi survei terlebih dahulu', 'data' => null], 500);
            }

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
            $data = SurveiPeserta::where('id', $id)->firstOrFail();
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
