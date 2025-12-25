<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\AdminSeleksi;
use Illuminate\Http\Request;
use App\Models\SurveiPeserta;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VerifikatorPendaftar;
use App\Http\Requests\AdminSeleksiRequest;
use App\Http\Resources\AdminSeleksiResource;

class AdminSeleksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $beasiswa_id = null)
    {

        $dataQuery = AdminSeleksi::with(['beasiswa.jenisBeasiswa', 'user.identitas'])
            ->withCount(['pendaftar'])
            ->orderBy('beasiswa_id', 'asc')
            ->orderBy('user_id', 'asc');

        if ($beasiswa_id) {
            $dataQuery->where('beasiswa_id', $beasiswa_id);
        }

        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        // if (!izinkanAkses('admin')) {
        //     $dataQuery->where('user_id', auth()->user()->id);
        // }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        if ($limit > 0) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(function ($item) {
                return new AdminSeleksiResource($item);
            });
            $data->setCollection($resourceCollection);
        } else
            $data = $dataQuery->get();

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        if ($limit > 0) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(function ($item) {
                return new AdminSeleksiResource($item);
            });
            $data->setCollection($resourceCollection);
        } else
            $data = $dataQuery->get();


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
    public function store(AdminSeleksiRequest $request, string $beasiswa_id)
    {
        try {
            DB::beginTransaction();
            $datasave = $request->validated();
            $data = AdminSeleksi::create($datasave);
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
            $dataQuery = AdminSeleksi::with(['beasiswa', 'user.identitas'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new AdminSeleksiResource($dataQuery),
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
    public function update(AdminSeleksiRequest $request, string $beasiswa_id, string $id)
    {
        try {
            DB::beginTransaction();
            $data = AdminSeleksi::where('id', $id)->firstOrFail();

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
            $data = AdminSeleksi::where('id', $id)->firstOrFail();
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
