<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Models\SoalWawancara;
use App\Models\WawancaraNilai;
use App\Models\PesertaWawancara;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\WawancaraNilaiRequest;
use App\Http\Resources\WawancaraNilaiResource;
use App\Http\Requests\NomorWawancaraNilaiRequest;

class WawancaraNilaiController extends Controller
{

    public function prosesWawancara(Request $request, string $id)
    {
        try {
            $data = SoalWawancara::with([
                'wawancaraNilai' => function ($query) use ($id) {
                    $query->where('pendaftar_id', $id)
                        ->whereHas('pewawancara', function ($q) {
                            $q->where('user_id', auth()->user()->id);
                        });
                }
            ])
                ->where("beasiswa_id", $request->beasiswa_id)
                ->paginate(1);

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data,
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = WawancaraNilai::with(['beasiswa'])->orderBy('beasiswa_id', 'asc')->orderBy('nomor', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new WawancaraNilaiResource($item);
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
    public function store(WawancaraNilaiRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = WawancaraNilai::create($request->validated());
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
            $dataQuery = WawancaraNilai::with(['beasiswa'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new WawancaraNilaiResource($dataQuery),
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
    public function update(WawancaraNilaiRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = WawancaraNilai::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function pengelolaAkhiriWawancara(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = PesertaWawancara::with(['pendaftar'])->where('id', $id)->firstOrFail();

            $totalJawaban = WawancaraNilai::where('pendaftar_id', $data->pendaftar_id)
                ->whereHas('pewawancara', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })
                ->count();

            $totalSoal = SoalWawancara::where('beasiswa_id', $data->pendaftar->beasiswa_id)->count();
            if ($totalJawaban < $totalSoal) {
                return response()->json(['status' => false, 'message' => 'Masih ada soal wawancara yang belum dinilai', 'data' => null], 500);
            }
            $totalNilai = WawancaraNilai::where('pendaftar_id', $data->pendaftar_id)
                ->whereHas('pewawancara', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })
                ->sum('nilai');
            $data->update(['nilai' => ($totalNilai / $totalSoal), 'status' => "2"]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function akhiriWawancara(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = PesertaWawancara::with(['pendaftar'])->where('id', $id)->firstOrFail();

            $totalJawaban = WawancaraNilai::where('pendaftar_id', $data->pendaftar_id)
                ->whereHas('pewawancara', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })
                ->count();

            $totalSoal = SoalWawancara::where('beasiswa_id', $data->pendaftar->beasiswa_id)->count();
            if ($totalJawaban < $totalSoal) {
                return response()->json(['status' => false, 'message' => 'Masih ada soal wawancara yang belum dinilai', 'data' => null], 500);
            }
            $totalNilai = WawancaraNilai::where('pendaftar_id', $data->pendaftar_id)
                ->whereHas('pewawancara', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })
                ->sum('nilai');
            $data->update(['nilai' => ($totalNilai / $totalSoal), 'status' => "2"]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function gantiNomorWawancaraNilai(NomorWawancaraNilaiRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = WawancaraNilai::where('id', $id)->firstOrFail();
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
            $data = WawancaraNilai::where('id', $id)->firstOrFail();
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
