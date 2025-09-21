<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Mahasiswa;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Models\PesertaWawancara;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VerifikatorPendaftar;
use App\Http\Requests\PendaftarRequest;
use App\Http\Resources\PendaftarResource;
use App\Http\Requests\PendaftaranBatalRequest;
use App\Http\Resources\DaftarPendaftarResource;
use App\Http\Resources\DetailPendaftarResource;
use App\Http\Requests\PendaftaranKembaliRequest;
use App\Http\Resources\CetakIdentitasKartuPendaftaranResource;

class PendaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function daftarPendaftar(Request $request, $id_beasiswa)
    {
        $dataQuery = Pendaftar::with([
            'verifikatorPendaftar.verifikator.user',
            'beasiswa.jenisBeasiswa',
            'beasiswa.syarat',
            'uploadSyarat.syarat',
            'kelulusan',
            'mahasiswa.programStudi.fakultas',
            'mahasiswa.user.identitas',
            'mahasiswa.user.pendidikanAkhir'
        ])->where('beasiswa_id', $id_beasiswa)->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;

            $dataQuery->where(function ($query) use ($search) {
                $query->whereHas('mahasiswa.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }


        if ($request->filled('pendaftaran')) {
            $pendaftaran = $request->pendaftaran;
            switch ($pendaftaran) {
                case 'selesai':
                    $dataQuery->where(function ($query) {
                        $query->where('is_finalisasi', 1);
                    });
                    break;
                case 'belum':
                    $dataQuery->where(function ($query) {
                        $query->where('is_finalisasi', 0)->where('is_batal', 0);
                    });
                    break;
                case 'batal':
                    $dataQuery->where(function ($query) {
                        $query->where('is_batal', 1);
                    });
                    break;
            }
        }

        if ($request->filled('verifikasi')) {
            $verifikasi = $request->verifikasi;
            switch ($verifikasi) {
                case 'ms':
                    $dataQuery->where(function ($query) {
                        $query->whereHas('verifikatorPendaftar', function ($q) {
                            $q->where('hasil', 1);
                        });
                    });
                    break;
                case 'tms':
                    $dataQuery->where(function ($query) {
                        $query->whereHas('verifikatorPendaftar', function ($q) {
                            $q->where('hasil', 0);
                        });
                    });
                    break;
                case 'selesai':
                    $dataQuery->where(function ($query) {
                        $query->whereHas('verifikatorPendaftar', function ($q) {
                            $q->whereNotNull('hasil');
                        });
                    });
                    break;
                case 'belum':
                    $dataQuery->where(function ($query) {
                        $query->whereHas('verifikatorPendaftar', function ($q) {
                            $q->whereNull('hasil');
                        });
                    });
                    break;
            }
        }

        if ($request->filled('kelulusan')) {
            $kelulusan = $request->kelulusan;
            switch ($kelulusan) {
                case 'l':
                    $dataQuery->where(function ($query) {
                        $query->whereHas('kelulusan', function ($q) {
                            $q->where('is_lulus', 1);
                        });
                    });
                    break;
                case 'tl':
                    $dataQuery->where(function ($query) {
                        $query->whereHas('kelulusan', function ($q) {
                            $q->where('is_lulus', 0);
                        });
                    });
                    break;
                case 'selesai':
                    $dataQuery->where(function ($query) {
                        $query->whereHas('kelulusan', function ($q) {
                            $q->whereNotNull('is_lulus');
                        });
                    });
                    break;
                case 'belum':
                    $dataQuery->where(function ($query) {
                        $query->whereHas('kelulusan', function ($q) {
                            $q->whereNull('is_lulus');
                        });
                    });
                    break;
            }
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        if ($limit == 0) {
            $data = DaftarPendaftarResource::collection($dataQuery->get());
        } else {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(function ($item) {
                return new DaftarPendaftarResource($item);
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

    public function index(Request $request)
    {
        $dataQuery = Beasiswa::with(['jenisBeasiswa', 'syarat'])
            ->withCount(['pendaftar'])
            ->with(['pendaftar' => function ($query) use ($request) {
                $mahasiswa = Mahasiswa::where('user_id', auth()->user()->id)->first();
                $query->where('mahasiswa_id', $mahasiswa->id); //->where('is_batal', 0);
            }])
            ->orderBy('daftar_mulai', 'desc')
            ->orderBy('nama', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        if (!$request->filled('show_all')) {
            $dataQuery->where('is_aktif', 1);
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new PendaftarResource($item);
        });
        $data->setCollection($resourceCollection);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    private function generateNomorPendaftar($beasiswa_id)
    {
        $lastPendaftar = Pendaftar::where('beasiswa_id', $beasiswa_id)
            ->orderBy('no_pendaftaran', 'desc')
            ->first();
        $nextNumber = $lastPendaftar ? ((int) $lastPendaftar->no_pendaftaran + 1) : 1;
        return str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PendaftarRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_post = $request->validated();

            $data_post['is_batal'] = 0;
            $data = Pendaftar::create($data_post);

            // $data->update([
            //     'url_id' => date('Y') . '-' . $data->beasiswa_id . '-' . rand(100000, 999999) . '-' . $data->id
            // ]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function detailPendaftar(string $id)
    {
        try {
            $dataQuery = Pendaftar::with([
                'beasiswa.jenisBeasiswa',
                'beasiswa.syarat' => function ($query) use ($id) {
                    $query->with(['uploadSyarat' => function ($q) use ($id) {
                        $q->where('pendaftar_id', $id);
                    }]);
                },
                'mahasiswa.user.identitas.wilayahDesa.wilayahKecamatan.wilayahKabupaten.wilayahProvinsi',
                'mahasiswa.programStudi.fakultas',
                'kelulusan'
            ])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new DetailPendaftarResource($dataQuery),
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $dataQuery = Beasiswa::with([
                'jenisBeasiswa',
                'pendaftar' => function ($query) use ($id) {
                    $query->where('id', $id);
                },
                'pendaftar.mahasiswa' => function ($query) {
                    $query->where('user_id', auth()->user()->id);
                },
                'pendaftar.kelulusan',
                'pendaftar.mahasiswa.user',
                'pendaftar.mahasiswa.user.identitas.wilayahDesa.wilayahKecamatan.wilayahKabupaten.wilayahProvinsi',
                'pendaftar.mahasiswa.programStudi.fakultas',
                'syarat' => function ($query) use ($id) {
                    $query->with(['uploadSyarat' => function ($q) use ($id) {
                        $q->where('pendaftar_id', $id);
                    }]);
                },
            ])
                ->selectRaw(
                    '*, 
                    CASE 
                        WHEN NOW() BETWEEN daftar_mulai AND daftar_selesai THEN true 
                    ELSE false 
                    END as is_pendaftaran_aktif'
                )->firstOrFail();

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new PendaftarResource($dataQuery),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }


    public function batalkanFinalisasi(string $id)
    {
        try {
            DB::beginTransaction();
            //update pendaftar
            $data = Pendaftar::where('id', $id)->firstOrFail();
            $dataUpdate = [
                'is_finalisasi' => 0,
            ];
            $data->update($dataUpdate);

            //hapus 
            $dataVerifikator = VerifikatorPendaftar::where('pendaftar_id', $id)->firstOrFail();
            $dataVerifikator->delete();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }


    public function getData(string $id)
    {
        try {

            $dataQuery = Pendaftar::with([
                'mahasiswa.user.identitas',
                'mahasiswa.programStudi.fakultas',
                'kelulusan',
                'beasiswa',
            ])
                ->where("id", $id)
                ->firstOrFail();

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
    public function update(PendaftarRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Pendaftar::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function pembatalan(PendaftaranBatalRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Pendaftar::with(['mahasiswa'])->where('id', $id)->firstOrFail();

            $data_save = [
                'is_batal' => 1,
                'alasan' => $request->alasan
            ];
            $data->update($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'pendaftaran berhasil dibatalkan', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat pembatalan pendaftaran : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function pendaftaranSelesai(string $id)
    {
        try {
            DB::beginTransaction();
            $data = Pendaftar::with(['mahasiswa', 'beasiswa'])->where('id', $id)->firstOrFail();


            //validasi kepemilikan user
            if ($data->mahasiswa->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }
            //validasi status pendaftaran
            $validasi = validasiPendaftaran($data->beasiswa_id);
            if (!$validasi->pendaftaran_aktif) {
                return response()->json(['status' => false, 'message' => 'pendaftaran telah tertutup'], 422);
            }

            $no_pendaftaran = ($data->no_pendaftaran) ? $data->no_pendaftaran : $this->generateNomorPendaftar($data->beasiswa_id);
            $url_id = date('Y') . '-' . $data->beasiswa_id . '-' . rand(100000, 999999) . '-' . $no_pendaftaran;
            $data_save = [
                'is_finalisasi' => 1,
                'no_pendaftaran' => $no_pendaftaran,
                'url_id' => $url_id,
            ];
            $data->update($data_save);

            //untuk data verifikator
            $data['verifikator'] = null;
            if ($data->beasiswa->ada_verifikasi_berkas == 1) {
                // cari verifikator
                $verifikator_id = cariVerifikatorBerkas($data->beasiswa_id);
                if ($verifikator_id) {
                    $data_verifikator = VerifikatorPendaftar::firstOrCreate(
                        ["pendaftar_id" => $data->id], //cek dulu pendaftar_id, jika sudah ada batalkan
                        ["verifikator_id" => $verifikator_id]
                    );
                    $data['verifikator'] = $data_verifikator;
                }
            }

            //untuk data pewawancara
            $data['pewawancara'] = null;
            if ($data->beasiswa->ada_wawancara == 1) {
                // cari pewawancara
                $pewawancara_id = cariPewawancara($data->beasiswa_id);
                if ($pewawancara_id) {
                    $data_pewawancara = PesertaWawancara::firstOrCreate(
                        ["pendaftar_id"   => $data->id], //cari dulu pendaftar_id, jika ada maka batalkan
                        ["pewawancara_id" => $pewawancara_id]
                    );
                    $data['pewawancara'] = $data_pewawancara;
                }
            }


            DB::commit();
            return response()->json(['status' => true, 'message' => 'pendaftaran selesai dilakukan', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat mengakhiri pendaftaran : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function daftarKembali(PendaftaranKembaliRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Pendaftar::with(['mahasiswa'])->where('id', $id)->firstOrFail();

            $data_save = [
                'is_batal' => 0,
                'alasan' => null
            ];
            $data->update($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'pendaftaran kembali berhasil dibatalkan', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat pendaftaran kembali : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $data = Pendaftar::with(['mahasiswa'])->where('id', $id)->firstOrFail();

            $this->validasi($data->mahasiswa->user_id, $data->beasiswa_id);

            $data->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function dataPendaftar($url_id)
    {
        try {

            $pendaftar = Pendaftar::with([
                'verifikatorPendaftar.verifikator.user.identitas',
                'kelulusan',
                'mahasiswa.programStudi.fakultas',
                'mahasiswa.user.identitas.wilayahDesa.wilayahKecamatan.wilayahKabupaten.wilayahProvinsi',
            ])
                ->where('url_id', $url_id)
                ->firstOrFail();

            $dataPendaftar = new CetakIdentitasKartuPendaftaranResource($pendaftar);

            $beasiswa = Beasiswa::with([
                'jenisBeasiswa',
                'syarat' => function ($query) use ($pendaftar) {
                    $query->with(['uploadSyarat' => function ($q) use ($pendaftar) {
                        $q->where('pendaftar_id', $pendaftar->id);
                    }]);
                },
            ])
                ->where('id', $pendaftar->beasiswa_id)
                ->firstOrFail();

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => ['pendaftar' => $dataPendaftar, 'beasiswa' => $beasiswa],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }
}
