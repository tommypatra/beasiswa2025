<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Identitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\IdentitasRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\IdentitasResource;

class IdentitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = User::with(['identitas'])->orderBy('user_id', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $dataQuery->where('name', $search);
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new IdentitasResource($item);
        });
        $data->setCollection($resourceCollection);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }

    private function upload($request)
    {
        $file = $request->file('foto');
        $namaFile = time() . '_' . $file->getClientOriginalName(); // Nama unik
        $path_dokumen = 'foto/' . date('Y');
        if (!Storage::disk('public')->exists($path_dokumen)) {
            Storage::disk('public')->makeDirectory($path_dokumen);
        }
        return $file->storeAs($path_dokumen, $namaFile, 'public');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdentitasRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();

            $user_id = auth()->user()->id;
            $data = User::where('id', $user_id)->firstOrFail();
            $data->update($data_save);
            $respon['user'] = $data;

            $data_save['user_id'] = auth()->user()->id;
            $data_save['foto'] = upload($request->file('foto'), 'foto');
            $respon['identitas'] = Identitas::create($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $respon], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($data_save['kartu_Identitas'] && Storage::disk('public')->exists($data_save['kartu_Identitas'])) {
                Storage::disk('public')->delete($data_save['kartu_Identitas']);
            }
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data['user'] = User::where('id', $id)->firstOrFail();
            $data['identitas'] = Identitas::with(['wilayahDesa.WilayahKecamatan.WilayahKabupaten.wilayahProvinsi'])->where('user_id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new IdentitasResource($data),
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
    public function update(IdentitasRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();
            $user_id = auth()->user()->id;

            $user = User::where('id', $user_id)->firstOrFail();


            $user->update($data_save);
            $respon['user'] = $user;

            $identitas = Identitas::where('id', $id)->firstOrFail();
            //validasi kepemilikan user
            if (!izinkanAkses("Admin") &&  $identitas->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }


            if ($request->hasFile('foto')) {
                // Hapus file lama jika ada
                if ($identitas->foto && Storage::disk('public')->exists($identitas->foto)) {
                    Storage::disk('public')->delete($identitas->foto);
                }
                $data_save['foto'] = upload($request->file('foto'), 'foto');
            }
            $identitas->update($data_save);
            $respon['identitas'] = $identitas;

            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $respon], 200);
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
            $data = Identitas::where('id', $id)->firstOrFail();
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
