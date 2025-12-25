<?php

namespace App\Http\Controllers;

use App\Models\SesiUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SesiUjianRequest;
use App\Http\Resources\SesiUjianResource;

class SesiUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $beasiswa_id)
    {

        $dataQuery = SesiUjian::with(['beasiswa'])->orderBy('sesi', 'asc')
            ->where('beasiswa_id', $beasiswa_id);

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;

        if ($limit == 0) {
            $data = $dataQuery->get();
            $data = SesiUjianResource::collection($data);
        } else {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(function ($item) {
                return new SesiUjianResource($item);
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(SesiUjianRequest $request, string $beasiswa_id)
    {
        try {
            DB::beginTransaction();
            $data = SesiUjian::create($request->validated());
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
    public function show(string $beasiswa_id, string $id)
    {
        try {
            $dataQuery = SesiUjian::where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new SesiUjianResource($dataQuery),
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
    public function update(SesiUjianRequest $request, string $beasiswa_id, string $id)
    {
        try {
            DB::beginTransaction();
            $data = SesiUjian::where('id', $id)->firstOrFail();
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
    public function destroy(string $beasiswa_id, string $id)
    {
        try {
            DB::beginTransaction();
            $data = SesiUjian::where('id', $id)->firstOrFail();
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
