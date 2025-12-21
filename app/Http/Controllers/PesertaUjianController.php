<?php

namespace App\Http\Controllers;

use App\Models\PesertaUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PesertaUjianRequest;
use App\Http\Resources\PesertaUjianResource;

class PesertaUjianController extends Controller
{

    public function index(Request $request)
    {
        $dataQuery = PesertaUjian::with([
            'pendaftar.mahasiswa.user.identitas',
            'pendaftar.mahasiswa.programStudi.fakultas',
            'jadwalUjian.beasiswa',
            'jadwalUjian.ruanganUjian.ruangan'
        ])->orderBy('jadwal_ujian_id', 'asc')
            ->orderBy('pendaftar_id', 'asc');

        if ($request->filled('jadwal_ujian_id')) {
            $dataQuery->where('jadwal_ujian_id', $request->jadwal_ujian_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $dataQuery->where(function ($query) use ($search) {
                $query->whereHas('pendaftar.mahasiswa.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;

        if ($limit == 0) {
            $data = $dataQuery->get();
            $data = PesertaUjianResource::collection($data);
        } else {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->getCollection()->map(function ($item) {
                return new PesertaUjianResource($item);
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

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $data = PesertaUjian::where('id', $id)->firstOrFail();
            $data->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function store(PesertaUjianRequest $request)
    {
        try {
            DB::beginTransaction();
            //jika insert biasa
            // $data = PesertaUjian::create($request->validated());

            //insert tapi cek jika pendaftar_id kembar cukup update jadwal_ujian_id
            $validated = $request->validated();
            $data = PesertaUjian::updateOrCreate(
                ['pendaftar_id' => $validated['pendaftar_id']], // cek
                ['jadwal_ujian_id' => $validated['jadwal_ujian_id']] // data diubah
            );

            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function hapusPesertaUjian(string $id)
    {
        try {
            DB::beginTransaction();
            PesertaUjian::where('beasiswa_id', $id)->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }
}
