<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Verifikator;
use App\Models\UploadSyarat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VerifikatorPendaftar;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\VerifikasiBerkasRequest;
use App\Http\Resources\IdentitasPesertaResource;
use App\Http\Resources\VerifikasiBerkasResource;
use App\Http\Requests\ReuploadDokumenSyaratRequest;

class VerifikasiBerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Verifikator::with([
            'beasiswa',
            'user'
        ])
            ->withCount('verifikatorPendaftar')
            ->withCount([
                'verifikatorPendaftar as verifikator_pendaftar_valid' => function ($query) {
                    $query->whereNotNull('hasil');
                }
            ])
            ->where(function ($query) use ($request) {
                $query->WhereHas('beasiswa', function ($q) use ($request) {
                    $q->where('is_aktif', 1);
                });
            })
            ->where('user_id', auth()->user()->id)
            ->orderBy('beasiswa_id', 'asc')
            ->orderBy('user_id', 'asc');

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new VerifikasiBerkasResource($item);
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
        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $page = $request->filled('page') ? (int) $request->page : 1;

        $dataQuery = VerifikatorPendaftar::with([
            'verifikator',
            'pendaftar.mahasiswa.programStudi.fakultas',
            'pendaftar.mahasiswa.user.identitas'
        ])
            ->whereHas('verifikator', function ($q) use ($request) {
                $q->where('user_id', auth()->id())
                    ->where('beasiswa_id', $request->beasiswa_id);
            })
            ->whereHas('pendaftar', function ($q) use ($request) {
                $q->where('beasiswa_id', $request->beasiswa_id);
            });
        // tambahkan kondisi pencarian sebelum paginate
        if ($request->filled('search')) {
            $search = $request->search;
            $dataQuery->whereHas('pendaftar.mahasiswa.user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('verifikasi')) {
            $verifikasi = $request->verifikasi;
            switch ($verifikasi) {
                case 'ms':
                    $dataQuery->where('hasil', 1);
                    break;
                case 'tms':
                    $dataQuery->where('hasil', 0);
                    break;
                case 'selesai':
                    $dataQuery->whereNotNull('hasil');
                    break;
                case 'belum':
                    $dataQuery->whereNull('hasil');
                    break;
            }
        }

        // tambahkan kondisi pencarian sebelum paginate
        if ($request->filled('pendaftar_id')) {
            $pendaftar_id = $request->pendaftar_id;
            $dataQuery->whereHas('pendaftar', function ($q) use ($pendaftar_id) {
                $q->where('id', $pendaftar_id);
            });
        }

        // urutan dan pagination dilakukan terakhir
        $dataQuery = $dataQuery->orderBy('pendaftar_id', 'asc')
            ->paginate($limit, ['*'], 'page', $page);

        // ubah ke resource
        $resourceCollection = $dataQuery->getCollection()->map(fn($item) => new IdentitasPesertaResource($item));
        $dataQuery->setCollection($resourceCollection);

        return response()->json([
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $dataQuery,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VerifikasiBerkasRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = VerifikasiBerkas::create($request->validated());



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
            $dataQuery = VerifikasiBerkas::where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new VerifikasiBerkasResource($dataQuery),
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
    public function update(VerifikasiBerkasRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = VerifikasiBerkas::where('id', $id)->firstOrFail();
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
            $data = VerifikasiBerkas::where('id', $id)->firstOrFail();
            $data->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function reuploadDokumenSyarat(ReuploadDokumenSyaratRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();
            $data_save['dokumen'] = upload($request->file('dokumen'), 'dokumen');
            if (!$data_save['dokumen']) {
                throw new \Exception('Gagal mengunggah file dokumen');
            }


            $upload_sebelumnya = UploadSyarat::where('syarat_id', $data_save['syarat_id'])
                ->where('pendaftar_id', $data_save['pendaftar_id'])
                ->first();

            if ($upload_sebelumnya) {
                if ($upload_sebelumnya->dokumen && Storage::disk('public')->exists($upload_sebelumnya->dokumen)) {
                    Storage::disk('public')->delete($upload_sebelumnya->dokumen);
                }
                $upload_sebelumnya->update($data_save);
                $data = $data_save;
                $message = 'Dokumen berhasil diperbarui.';
            } else {
                $data = UploadSyarat::create($data_save);
                $message = 'Dokumen baru berhasil diupload.';
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => $message, 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($data_save['dokumen'] && Storage::disk('public')->exists($data_save['dokumen'])) {
                Storage::disk('public')->delete($data_save['dokumen']);
            }
            $pesan_salah = $e->getMessage();
            if ($e->getCode() == 23000) {
                $pesan_salah = "Hapus dulu dokumen upload sebelumnya, setelah itu upload lagi kembali.";
            }
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan saat membuat data baru: ' . $pesan_salah], 500);
        }
    }
}
