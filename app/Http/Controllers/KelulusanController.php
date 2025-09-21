<?php

namespace App\Http\Controllers;

use App\Models\Rumah;
use App\Models\Beasiswa;
use App\Models\OrangTua;
use App\Models\Kelulusan;
use App\Models\Pendaftar;
use App\Models\NilaiRaport;
use App\Models\Pewawancara;
use App\Models\UploadSyarat;
use Illuminate\Http\Request;
use App\Models\WawancaraNilai;
use App\Models\PendidikanAkhir;
use App\Models\PesertaWawancara;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VerifikatorPendaftar;
use App\Http\Requests\KelulusanRequest;
use App\Http\Resources\KelulusanResource;
use App\Http\Resources\DataPesertaLulusResource;
use App\Http\Requests\SimpanValidasiFinalRequest;
use App\Http\Requests\SimpanValidasiSyaratRequest;

class KelulusanController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Kelulusan::with([
            'pendaftar.surveiPeserta',
            'pendaftar.beasiswa',
            'pendaftar.pesertaWawancara.pewawancara.user',
            'pendaftar.mahasiswa.user.identitas',
            'pendaftar.mahasiswa.programStudi.fakultas'
        ]);

        $dataQuery->where(function ($query) {
            $query->whereHas('pendaftar', function ($q) {
                $q->where('is_finalisasi', 1);
            });
        });

        $filters = $request->input('filter', []);

        foreach ($filters as $key => $val) {
            if ($val === null || $val === '') continue;

            switch ($key) {
                case 'status_lulus':
                    if ($val != 2)
                        $dataQuery->where(function ($query) use ($val) {
                            $query->where('is_lulus', $val);
                        });
                    else
                        $dataQuery->where(function ($query) {
                            $query->whereNull('is_lulus');
                        });
                    break;
                default:
                    $dataQuery->where(function ($query) {
                        $dataQuery->where($key, $val);
                    });
                    break;
            }
        }

        if ($request->filled('sort')) {
            foreach ($request->sort as $col) {
                if ($col) {
                    $dataQuery->orderBy($col, 'desc');
                }
            }
        } else {
            $dataQuery->orderBy('nilai_wawancara', 'desc');
        }

        if ($request->filled('beasiswa_id')) {
            $beasiswa_id = $request->beasiswa_id;

            $dataQuery->where(function ($query) use ($beasiswa_id) {
                $query->whereHas('pendaftar', function ($q) use ($beasiswa_id) {
                    $q->where('beasiswa_id', $beasiswa_id);
                });
            });
        }


        if ($request->filled('search')) {
            $search = $request->search;

            $dataQuery->where(function ($query) use ($search, $request) {
                $query->whereHas('pendaftar.mahasiswa.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });

                if ($request->filled('cari_pewawancara')) {
                    $query->orWhereHas('pendaftar.pesertaWawancara.pewawancara.user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                }
            });
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new KelulusanResource($item);
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
    public function store(KelulusanRequest $request)
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

            Kelulusan::insert($bulkInsertData);

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

    function dataPesertaLulus(Request $request, $beasiswa_id, $sk_penerima_id)
    {
        $dataQuery = Kelulusan::with([
            'pendaftar.beasiswa',
            'pendaftar.mahasiswa.user',
            'pendaftar.mahasiswa.user.identitas',
            'pendaftar.mahasiswa.programStudi.fakultas',
            'pendaftar.mahasiswa.user.penerima' => function ($q) use ($sk_penerima_id) {
                $q->where('sk_penerima_id', $sk_penerima_id);
            }
        ])
            ->where('is_lulus', 1)
            ->whereHas('pendaftar.beasiswa', function ($q) use ($beasiswa_id) {
                $q->where('id', $beasiswa_id);
            });

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);

        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new DataPesertaLulusResource($item);
        });
        $data->setCollection($resourceCollection);


        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    public function prosesKelulusan(Request $request)
    {
        $request->validate([
            'beasiswa_id' => 'required|exists:beasiswas,id',
            'pendaftar_id' => 'required|exists:pendaftars,id',
        ]);

        try {
            DB::beginTransaction();

            $beasiswa = $request->input('beasiswa');
            $pendaftar_id = $request->pendaftar_id;

            $peserta = Pendaftar::with(['mahasiswa.user'])
                ->where('id', $pendaftar_id)
                ->firstOrFail();

            $user_id = $peserta->mahasiswa->user_id;

            $data_post = [
                'user_id' => $user_id,
                'name' => $peserta->mahasiswa->user->name,
                'email' => $peserta->mahasiswa->user->email,
                'pendaftar_id' => $pendaftar_id,
                'nilai_survei' => null,
                'nilai_cbt' => null,
                'nilai_berkas' => null,
                'nilai_orang_tua' => null,
                'nilai_raport' => null,
                'nilai_pendidikan_akhir' => null,
                'nilai_rumah' => null,
                'nilai_ekonomi' => null,
                'nilai_pendidikan' => null,
                'nilai_wawancara' => null,
                'is_lulus' => null,
                'catatan' => null,
            ];

            // Ambil nilai orang tua
            $respOrtu = (new OrangTuaController())->dataOrangTua($user_id)->getData();
            $data_post['nilai_orang_tua'] = (float)($respOrtu->data->verifikasi_lapangan_skor
                ?? $respOrtu->data->skor_akhir
                ?? 0);

            // Ambil nilai raport
            $respRaport = (new NilaiRaportController())->dataRaport($user_id)->getData();
            $data_post['nilai_raport'] = (float)($respRaport->data->verifikasi_lapangan_skor
                ?? $respRaport->data->skor_akhir
                ?? 0);

            // Ambil nilai pendidikan akhir
            $respPendidikan = (new PendidikanAkhirController())->dataPendidikanAkhir($user_id)->getData();
            $data_post['nilai_pendidikan_akhir'] = (float)($respPendidikan->data->verifikasi_lapangan_skor
                ?? $respPendidikan->data->skor_akhir
                ?? 0);

            // Ambil nilai rumah
            $respRumah = (new RumahController())->dataKondisiRumah($user_id)->getData();
            $data_post['nilai_rumah'] = (float)($respRumah->data->verifikasi_lapangan_skor
                ?? $respRumah->data->skor_akhir
                ?? 0);

            // Ambil nilai berkas upload
            $respBerkas = (new UploadSyaratController())->dataDokumenUpload($pendaftar_id)->getData();
            if (!empty($respBerkas->data->verifikasi_berkas)) {
                $data_post['nilai_survei'] = (float)$respBerkas->data->verifikasi_berkas->verifikasi_lapangan_skor;
                $data_post['nilai_berkas'] = (float)$respBerkas->data->verifikasi_berkas->total_skor;
            }

            // Nilai wawancara
            // if (($beasiswa['ada_wawancara'] ?? 0) === 1) {
            $data_post['nilai_wawancara'] = (float)PesertaWawancara::where('pendaftar_id', $pendaftar_id)->avg('nilai');
            // }

            $data_post['nilai_ekonomi'] = ($data_post['nilai_rumah'] + $data_post['nilai_orang_tua']) / 2;
            $data_post['nilai_pendidikan'] = ($data_post['nilai_pendidikan_akhir'] + $data_post['nilai_raport']) / 2;

            // Simpan data kelulusan
            $kelulusan = Kelulusan::updateOrCreate(
                ['pendaftar_id' => $data_post['pendaftar_id']],
                $data_post
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data kelulusan berhasil diproses',
                'data' => $kelulusan
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memproses kelulusan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $dataQuery = Kelulusan::with(['pendaftar.mahasiswa.user.identitas', 'pendaftar.beasiswa'])->where('id', $id)->firstOrFail();
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
            $data = Kelulusan::where('id', $id)->firstOrFail();
            $data_save = [
                'is_lulus' => $request->is_lulus,
            ];
            $data->update($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function hapusKelulusan($beasiswa_id)
    {
        try {
            DB::beginTransaction();
            Kelulusan::whereHas('pendaftar', function ($q) use ($beasiswa_id) {
                $q->where('beasiswa_id', $beasiswa_id);
            })->delete();
            DB::commit();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $data = Kelulusan::where('id', $id)->firstOrFail();
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
