<?php

namespace App\Http\Controllers;

use App\Models\Rumah;
use App\Models\OrangTua;
use App\Models\Pendaftar;
use App\Models\SurveiNilai;
use App\Models\UploadSyarat;
use Illuminate\Http\Request;
use App\Models\SoalWawancara;
use App\Models\PendidikanAkhir;
use App\Models\PesertaWawancara;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SurveiNilaiRequest;
use App\Http\Resources\SurveiNilaiResource;
use App\Http\Requests\SurveiLapanganRequest;
use App\Http\Requests\NomorSurveiNilaiRequest;
use App\Models\NilaiRaport;
use App\Models\VerifikatorPendaftar;

class SurveiNilaiController extends Controller
{

    public function prosesWawancara(Request $request, string $id)
    {
        try {
            $data = SoalWawancara::with([
                'SurveiNilai' => function ($query) use ($id) {
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
        $dataQuery = SurveiNilai::with(['beasiswa'])->orderBy('beasiswa_id', 'asc')->orderBy('nomor', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new SurveiNilaiResource($item);
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
    public function store(SurveiNilaiRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = SurveiNilai::create($request->validated());
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
            $dataQuery = SurveiNilai::with(['beasiswa'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new SurveiNilaiResource($dataQuery),
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
    public function update(SurveiNilaiRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = SurveiNilai::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }


    public function updateSurveiPendidikanAkhir(SurveiLapanganRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = PendidikanAkhir::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function updateSurveiRaport(SurveiLapanganRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = NilaiRaport::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function updateSurveiKondisiRumah(SurveiLapanganRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Rumah::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function updateSurveiOrangTUa(SurveiLapanganRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = OrangTua::where('id', $id)->firstOrFail();
            $data->update($request->validated());
            DB::commit();
            return response()->json(['status' => true, 'message' => 'berhasil diperbarui', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function updateSurveiDokumenUpload(SurveiLapanganRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = VerifikatorPendaftar::where('id', $id)->firstOrFail();
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

            $totalJawaban = SurveiNilai::where('pendaftar_id', $data->pendaftar_id)
                ->whereHas('pewawancara', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })
                ->count();

            $totalSoal = SoalWawancara::where('beasiswa_id', $data->pendaftar->beasiswa_id)->count();
            if ($totalJawaban < $totalSoal) {
                return response()->json(['status' => false, 'message' => 'Masih ada soal wawancara yang belum dinilai', 'data' => null], 500);
            }
            $totalNilai = SurveiNilai::where('pendaftar_id', $data->pendaftar_id)
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

            $totalJawaban = SurveiNilai::where('pendaftar_id', $data->pendaftar_id)
                ->whereHas('pewawancara', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })
                ->count();

            $totalSoal = SoalWawancara::where('beasiswa_id', $data->pendaftar->beasiswa_id)->count();
            if ($totalJawaban < $totalSoal) {
                return response()->json(['status' => false, 'message' => 'Masih ada soal wawancara yang belum dinilai', 'data' => null], 500);
            }
            $totalNilai = SurveiNilai::where('pendaftar_id', $data->pendaftar_id)
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

    public function gantiNomorSurveiNilai(NomorSurveiNilaiRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = SurveiNilai::where('id', $id)->firstOrFail();
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
            $data = SurveiNilai::where('id', $id)->firstOrFail();
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
