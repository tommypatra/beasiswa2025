<?php

namespace App\Http\Controllers;

use App\Models\UploadSyarat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadSyaratRequest;
use App\Http\Resources\UploadSyaratResource;
use Illuminate\Support\Facades\Storage;

class UploadSyaratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = UploadSyarat::orderBy('id', 'asc');

        // if ($request->filled('search')) {
        //     $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        // }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new UploadSyaratResource($item);
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
    public function store(UploadSyaratRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();
            $data_save['dokumen'] = upload($request->file('dokumen'), 'dokumen');

            $data = UploadSyarat::create($data_save);
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
            $dataQuery = UploadSyarat::where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new UploadSyaratResource($dataQuery),
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
    public function update(UploadSyaratRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = UploadSyarat::where('id', $id)->firstOrFail();
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
            $data = UploadSyarat::with(['syarat', 'pendaftar.mahasiswa'])->where('id', $id)->firstOrFail();

            //validasi kepemilikan user
            if ($data->pendaftar->mahasiswa->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }
            //validasi status pendaftaran
            $validasi = validasiPendaftaran($data->syarat->beasiswa_id);
            if (!$validasi->pendaftaran_aktif) {
                return response()->json(['status' => false, 'message' => 'pendaftaran telah tertutup'], 422);
            }

            if ($data->dokumen && Storage::disk('public')->exists($data->dokumen)) {
                Storage::disk('public')->delete($data->dokumen);
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
