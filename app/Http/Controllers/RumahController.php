<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\RumahRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\RumahResource;

class RumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Rumah::with(['user'])->orderBy('id', 'asc');

        if ($request->filled('user_id')) {
            $user_id = $request->user_id;
            $dataQuery->where('user_id', $user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $dataQuery->where('name', $search);
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new RumahResource($item);
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
    public function store(RumahRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();

            $data_save['user_id'] = auth()->user()->id;
            // $data_save['foto_depan'] = upload($request->file('foto_depan'), 'foto_depan');
            // $data_save['foto_belakang'] = upload($request->file('foto_belakang'), 'foto_belakang');
            // $data_save['foto_ruang_tamu'] = upload($request->file('foto_ruang_tamu'), 'foto_ruang_tamu');
            // $data_save['foto_ruang_tengah'] = upload($request->file('foto_ruang_tengah'), 'foto_ruang_tengah');
            // $data_save['foto_dapur'] = upload($request->file('foto_dapur'), 'foto_dapur');
            // $data_save['foto_wc'] = upload($request->file('foto_wc'), 'foto_wc');
            $data = Rumah::create($data_save);
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
            $data = Rumah::where('user_id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new RumahResource($data),
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
    public function update(RumahRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();

            $data = Rumah::where('id', $id)->firstOrFail();
            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }
            $data->update($data_save);

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
            $data = Rumah::where('id', $id)->firstOrFail();
            //validasi kepemilikan user
            if ($data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
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
