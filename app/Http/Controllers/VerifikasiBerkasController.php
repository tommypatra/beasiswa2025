<?php

namespace App\Http\Controllers;

use App\Models\Verifikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\VerifikatorPendaftar;
use App\Http\Requests\VerifikasiBerkasRequest;
use App\Http\Resources\IdentitasPesertaResource;
use App\Http\Resources\VerifikasiBerkasResource;

class VerifikasiBerkasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Verifikator::with([
            'beasiswa',
            'user'
        ])
            ->withCount('verifikatorPendaftar')
            ->withCount([
                'verifikatorPendaftar as verifikator_pendaftar_valid' => function ($query) {
                    $query->whereNotNull('hasil');
                }
            ])
            ->where('user_id', auth()->user()->id)
            ->orderBy('beasiswa_id', 'asc')
            ->orderBy('user_id', 'asc');

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new VerifikasiBerkasResource($item);
        });
        $data->setCollection($resourceCollection);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $data,
        ];
        return response()->json($dataRespon);
    }


    public function pesertaVerifikasi(Request $request)
    {
        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $page = $request->filled('page') ? (int) $request->page : 1;

        $dataQuery = VerifikatorPendaftar::with([
            'verifikator',
            'pendaftar.mahasiswa.programStudi',
            'pendaftar.mahasiswa.user.identitas'
        ])
            ->where(function ($query) use ($request) {
                $query->whereHas('verifikator', function ($q) use ($request) {
                    $q->where('user_id', auth()->user()->id)
                        ->Where('beasiswa_id',  $request->beasiswa_id);
                });
            })
            ->orderBy('pendaftar_id', 'asc')
            ->paginate($limit, ['*'], 'page', $page);


        $resourceCollection = $dataQuery->getCollection()->map(function ($item) {
            return new IdentitasPesertaResource($item);
        });
        $dataQuery->setCollection($resourceCollection);

        $dataRespon = [
            'status' => true,
            'message' => 'Pengambilan data dilakukan',
            'data' => $dataQuery,
        ];
        return response()->json($dataRespon);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(VerifikasiBerkasRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = VerifikasiBerkas::create($request->validated());
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
            $dataQuery = VerifikasiBerkas::where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new VerifikasiBerkasResource($dataQuery),
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
    public function update(VerifikasiBerkasRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = VerifikasiBerkas::where('id', $id)->firstOrFail();
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
            $data = VerifikasiBerkas::where('id', $id)->firstOrFail();
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
