<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use App\Models\ReferensiPilihan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrangTuaRequest;
use App\Http\Resources\OrangTuaResource;
use App\Http\Resources\DataOrangTuaResource;

class OrangTuaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = OrangTua::with(['user.mahasiswa.programstudi.fakultas', 'user.identitas'])->orderBy('user_id', 'asc')->orderBy('bapak_nama', 'asc')->orderBy('ibu_nama', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where(function ($query) use ($request) {
                $query->where('bapak_nama', 'like', '%' . $request->search . '%')
                    ->orWhere('ibu_nama', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('user_id')) {
            $user_id = $request->user_id;
            $dataQuery->where('user_id', $user_id);
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new OrangTuaResource($item);
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
    public function store(OrangTuaRequest $request)
    {
        try {
            DB::beginTransaction();
            $datasave = $request->validated();
            $datasave['user_id'] = auth()->user()->id;
            $data = OrangTua::create($datasave);
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
            $dataQuery = OrangTua::with(['user', 'user.identitas'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new OrangTuaResource($dataQuery),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    public function dataOrangTua(string $id)
    {
        try {
            $grupTerpilih = ['Pekerjaan', 'Pendidikan', 'Pendapatan'];

            $jumlahPilihan = ReferensiPilihan::select('grup', DB::raw('count(*) as total'))
                ->whereIn('grup', $grupTerpilih)
                ->groupBy('grup')
                ->get()
                ->mapWithKeys(function ($item) {
                    $key = strtolower(str_replace(' ', '_', $item->grup));
                    return [$key => $item->total];
                });

            $data = User::with([
                'orangTua.pekerjaanBapak',
                'orangTua.pekerjaanIbu',
                'orangTua.pendapatanBapak',
                'orangTua.pendapatanIbu',
                'orangTua.pendidikanBapak',
                'orangTua.pendidikanIbu',
                // 'rumah.pilihanKepemilikanRumah',
                // 'rumah.pilihanMck',
                // 'rumah.pilihanListrik',
                // 'rumah.pilihanSumberAir',
                // 'rumah.pilihanSumberListrik'
            ])->where('id', $id)->firstOrFail();

            $respon_data = new DataOrangTuaResource($data, $jumlahPilihan);

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $respon_data,
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
    public function update(OrangTuaRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = OrangTua::where('id', $id)->firstOrFail();

            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }

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
            $data = OrangTua::where('id', $id)->firstOrFail();
            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }

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
