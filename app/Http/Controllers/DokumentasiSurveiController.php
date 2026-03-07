<?php

namespace App\Http\Controllers;

// use App\Models\Beasiswa;
use Illuminate\Http\Request;
use App\Models\DokumentasiSurvei;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DokumentasiSurveiRequest;
use App\Http\Resources\DokumentasiSurveiResource;

class DokumentasiSurveiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $dataQuery = DokumentasiSurvei::with(['pendaftar.mahasiswa.user'])->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('pendaftar.mahasiswa.user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        if ($request->filled('pendaftar_id')) {
            $dataQuery->where('pendaftar_id', $request->pendaftar_id);
        }

        if ($request->filled('user_id')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('pendaftar.mahasiswa.user', function ($q) use ($request) {
                    $q->where('id', $request->user_id);
                });
            });
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new DokumentasiSurveiResource($item);
        });
        $data->setCollection($resourceCollection);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    public function dataDokumentasiSurvei($pendaftar_id)
    {
        try {
            $dataQuery = DokumentasiSurvei::with(['pendaftar.mahasiswa.user'])
                ->WhereHas('pendaftar', function ($q) use ($pendaftar_id) {
                    $q->where('id', $pendaftar_id);
                })->get();

            if ($dataQuery->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                    'data' => null,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new DokumentasiSurveiResource($dataQuery),
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
     * Store a newly created resource in storage.
     */
    public function store(DokumentasiSurveiRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();
            $data_save['path'] = upload($request->file('path'), 'path');
            if (!$data_save['path']) {
                throw new \Exception('Gagal mengunggah file');
            }

            $data = DokumentasiSurvei::create($data_save);
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
            $dataQuery = DokumentasiSurvei::with(['pendaftar.mahasiswa.user'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new DokumentasiSurveiResource($dataQuery),
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
    public function update(DokumentasiSurveiRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = DokumentasiSurvei::where('id', $id)->firstOrFail();

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
            $data = DokumentasiSurvei::where('id', $id)->firstOrFail();
            if ($data->path && Storage::disk('public')->exists($data->path)) {
                Storage::disk('public')->delete($data->path);
            }

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
