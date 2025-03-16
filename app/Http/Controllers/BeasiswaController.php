<?php

namespace App\Http\Controllers;

use App\Models\Beasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\BeasiswaRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\BeasiswaResource;

class BeasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Beasiswa::with(['jenisBeasiswa', 'syarat'])
            ->withCount(['pendaftar'])
            ->orderBy('daftar_mulai', 'desc')
            ->orderBy('nama', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('tahun')) {
            $dataQuery->where('tahun', $request->tahun);
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new BeasiswaResource($item);
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
    public function store(BeasiswaRequest $request)
    {
        try {
            DB::beginTransaction();
            $request->merge(['user_id' => auth()->user()->id]);
            $data = Beasiswa::create($request->validated());
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
            $dataQuery = Beasiswa::with([
                'jenisBeasiswa',
                'syarat',
            ])
                ->withCount([
                    'pendaftar as jumlah_finalisasi' => function ($query) {
                        $query->where('is_finalisasi', 1);
                    },
                    'pendaftar as jumlah_verifikator' => function ($query) {
                        $query->whereHas('verifikatorPendaftar'); // Menghitung pendaftar yang memiliki verifikator
                    }
                ])
                ->where('id', $id)
                ->firstOrFail();

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new BeasiswaResource($dataQuery),
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
    public function update(BeasiswaRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Beasiswa::where('id', $id)->firstOrFail();
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
            $data = Beasiswa::where('id', $id)->firstOrFail();
            $data->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    public function uploadGambarBeasiswa(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096', // Hanya gambar ≤4MB
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName(); // Nama unik

            $path_gambar = 'beasiswa/' . date('Y');
            if (!Storage::disk('public')->exists($path_gambar)) {
                Storage::disk('public')->makeDirectory($path_gambar);
            }
            $path = $file->storeAs($path_gambar, $namaFile, 'public');

            return response()->json([
                'status' => true,
                'message' => 'Gambar berhasil diupload',
                'data' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Gagal upload gambar'
        ], 400);
    }
}
