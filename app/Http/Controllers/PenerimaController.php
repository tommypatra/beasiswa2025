<?php

namespace App\Http\Controllers;

use App\Models\Penerima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PenerimaRequest;
use App\Http\Resources\PenerimaResource;

class PenerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Penerima::with([
            'bukuRekening',
            'user.identitas',
            'user.mahasiswa.programStudi.fakultas',
            'user.bukuRekening' => function ($q) {
                $q->where('is_aktif', 1);
            }
        ])->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;

            $dataQuery->where(function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })->orWhereHas('user.mahasiswa', function ($q) use ($search) {
                    $q->where('nim', 'like', "%$search%");
                });
            });
        }

        if ($request->filled('sk_penerima_id')) {
            $dataQuery->where('sk_penerima_id',  $request->sk_penerima_id);
        }


        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new PenerimaResource($item);
        });
        $data->setCollection($resourceCollection);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }


    public function skPenerimaMahasiswa(Request $request)
    {
        $user_id = auth()->user()->id;
        $dataQuery = Penerima::with([
            'SkPenerima.monitoring'
        ])->where('user_id', $user_id)->orderByDesc('id');

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


    public function gantiNomorRekening(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $data = Penerima::where('id', $id)->firstOrFail();
            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }
            $data_save['buku_rekening_id'] = $request->buku_rekening_id;
            $data->update($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PenerimaRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = Penerima::create($request->validated());
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
            $dataQuery = Penerima::where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new PenerimaResource($dataQuery),
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
    public function update(PenerimaRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Penerima::where('id', $id)->firstOrFail();
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
            $data = Penerima::where('id', $id)->firstOrFail();
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
