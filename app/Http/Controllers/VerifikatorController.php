<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Pendaftar;
use App\Models\Verifikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VerifikatorPendaftar;
use App\Http\Requests\VerifikatorRequest;
use App\Http\Resources\VerifikatorResource;

class VerifikatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getPesertaVerifikasi($beasiswa_id, $hasil)
    {
        if (!in_array($hasil, [0, 1])) {
            return response()->json([
                'status' => false,
                'message' => 'Parameter hasil harus 0 atau 1'
            ], 422);
        }

        $beasiswa = Beasiswa::select('id', 'ada_verifikasi_berkas', 'ada_verifikasi_lapangan', 'ada_ujian_cbt', 'ada_wawancara')
            ->find($beasiswa_id);
        if (!$beasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Beasiswa tidak ditemukan'
            ], 404);
        }

        $pendaftarIds = Pendaftar::where('beasiswa_id', $beasiswa_id)
            ->whereHas('verifikatorPendaftar', function ($query) use ($hasil) {
                $query->where('hasil', $hasil);
            })
            ->pluck('id');

        return response()->json([
            'status' => true,
            'beasiswa' => $beasiswa,
            'pendaftar_ids' => $pendaftarIds,
            'total' => $pendaftarIds->count()
        ]);
    }


    public function index(Request $request, $beasiswa_id)
    {
        $dataQuery = Verifikator::with(['beasiswa', 'user', 'verifikatorPendaftar.pendaftar.mahasiswa.user'])->where('beasiswa_id', $beasiswa_id)->orderBy('beasiswa_id', 'asc')->orderBy('user_id', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->WhereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
                $query->orWhereHas('verifikatorPendaftar.pendaftar.mahasiswa.user', function ($q) use ($request) {
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
            return new VerifikatorResource($item);
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
    public function store(VerifikatorRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_post = $request->validated();
            $data_post['user_id'] = auth()->user()->id;
            $data = Verifikator::create($data_post);
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
            $dataQuery = Verifikator::with(['beasiswa', 'user.identitas'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new VerifikatorResource($dataQuery),
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
    public function update(VerifikatorRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data_post = $request->validated();
            $data_post['user_id'] = auth()->user()->id;

            $data = Verifikator::where('id', $id)->firstOrFail();

            $data->update($data_post);
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
            $data = Verifikator::where('id', $id)->firstOrFail();
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
