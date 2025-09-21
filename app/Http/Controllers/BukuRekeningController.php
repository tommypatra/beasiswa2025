<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BukuRekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BukuRekeningRequest;
use App\Http\Resources\BukuRekeningResource;

class BukuRekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_id = auth()->user()->id;
        $dataQuery = BukuRekening::with(['user.mahasiswa.programStudi.fakultas', 'user.identitas'])->where('user_id', $user_id)->orderBy('id', 'asc');


        if ($request->filled('search')) {
            $dataQuery->where('bank', 'like', '%' . $request->search . '%')
                ->whereOr('nomor', 'like', '%' . $request->search . '%');
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new BukuRekeningResource($item);
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
    public function store(BukuRekeningRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();
            $data_save['user_id'] = auth()->user()->id;
            $data_save['foto_buku'] = upload($request->file('foto_buku'), 'foto_buku');


            if ($data_save['is_aktif'] == 1) {
                BukuRekening::where('user_id', auth()->user()->id)
                    ->where('is_aktif', 1)
                    ->update(['is_aktif' => null]);
            }

            $data = BukuRekening::create($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($data_save['foto_buku'] && Storage::disk('public')->exists($data_save['foto_buku'])) {
                Storage::disk('public')->delete($data_save['foto_buku']);
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
            $dataQuery = BukuRekening::with(['user.mahasiswa.programStudi.fakultas', 'user.identitas'])->where('id', $id)->firstOrFail();

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new BukuRekeningResource($dataQuery),
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
    public function update(BukuRekeningRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();

            $data = BukuRekening::where('id', $id)->firstOrFail();
            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }

            if ($request->hasFile('foto_buku')) {
                // Hapus file lama jika ada
                if ($data->foto_buku && Storage::disk('public')->exists($data->foto_buku)) {
                    Storage::disk('public')->delete($data->foto_buku);
                }
                $data_save['foto_buku'] = upload($request->file('foto_buku'), 'foto_buku');
            }

            if ($data_save['is_aktif'] == 1) {
                BukuRekening::where('user_id', auth()->user()->id)
                    ->where('is_aktif', 1)->where('id', '!=', $data->id)
                    ->update(['is_aktif' => null]);
            }


            $data->update($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function aktifkanRekening(string $id)
    {
        try {
            DB::beginTransaction();

            $data = BukuRekening::where('id', $id)->firstOrFail();
            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }

            BukuRekening::where('user_id', auth()->user()->id)
                ->where('is_aktif', 1)->where('id', '!=', $data->id)
                ->update(['is_aktif' => null]);

            $data->update(['is_aktif' => 1]);

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
            $data = BukuRekening::where('id', $id)->firstOrFail();
            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }

            $path = $data->foto_buku;
            $data->delete();

            DB::commit();
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }
}
