<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Surveyor;
use Illuminate\Http\Request;
use App\Models\SurveiPeserta;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VerifikatorPendaftar;
use App\Http\Requests\SurveyorRequest;
use App\Http\Resources\SurveyorResource;

class SurveyorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $beasiswa_id)
    {

        $dataQuery = Surveyor::with(['beasiswa', 'user', 'surveiPeserta.pendaftar.mahasiswa.user'])->where('beasiswa_id', $request->beasiswa_id)->orderBy('beasiswa_id', 'asc')->orderBy('user_id', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
                $query->orWhereHas('surveiPeserta.pendaftar.mahasiswa.user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        if ($request->filled('user_id')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('user', function ($q) use ($request) {
                    $q->where('id', $request->user_id);
                });
            });
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new SurveyorResource($item);
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
    public function store(SurveyorRequest $request, string $beasiswa_id)
    {
        try {
            DB::beginTransaction();
            $datasave = $request->validated();
            $data = Surveyor::create($datasave);
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
            $dataQuery = Surveyor::with(['beasiswa', 'user.identitas'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new SurveyorResource($dataQuery),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    public function batalkanFinalisasiSurveyor(string $beasiswa_id, string $id)
    {
        try {
            DB::beginTransaction();
            $data = SurveiPeserta::where('id', $id)->firstOrFail();

            $data->update(['hasil' => null]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(SurveyorRequest $request, string $beasiswa_id, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Surveyor::where('id', $id)->firstOrFail();

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
            $data = Surveyor::where('id', $id)->firstOrFail();
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
