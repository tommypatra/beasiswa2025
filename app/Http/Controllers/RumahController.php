<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rumah;
use Illuminate\Http\Request;
use App\Models\ReferensiPilihan;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RumahRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\RumahResource;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DataKondisiRumahResource;

class RumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Rumah::with(['user'])->orderBy('id', 'asc');

        if ($request->filled('user_id')) {
            $user_id = $request->user_id;
            $dataQuery->where('user_id', $user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $dataQuery->where('name', $search);
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new RumahResource($item);
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
    public function store(RumahRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();

            $data_save['user_id'] = auth()->user()->id;
            // $data_save['foto_rumah'] = upload($request->file('foto_rumah'), 'foto_rumah');
            if ($request->hasFile('foto_rumah')) {
                $data_save['foto_rumah'] = upload($request->file('foto_rumah'), 'foto_rumah');
                if (!$data_save['foto_rumah']) {
                    throw new \Exception('Gagal mengunggah foto rumah');
                }
            }

            $data = Rumah::create($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($data_save['foto_rumah'] && Storage::disk('public')->exists($data_save['foto_rumah'])) {
                Storage::disk('public')->delete($data_save['foto_rumah']);
            }


            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Rumah::where('user_id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new RumahResource($data),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }


    public function dataKondisiRumah(string $id)
    {
        try {

            $grupTerpilih = ['MCK', 'Sumber Listrik', 'Sumber Air', 'Kepemilikan Rumah', 'Listrik'];

            $jumlahPilihan = ReferensiPilihan::select('grup', DB::raw('count(*) as total'))
                ->whereIn('grup', $grupTerpilih)
                ->groupBy('grup')
                ->get()
                ->mapWithKeys(function ($item) {
                    $key = strtolower(str_replace(' ', '_', $item->grup));
                    return [$key => $item->total];
                });


            // print_r($jumlahPilihan);

            $data = User::with([
                'rumah.pilihanKepemilikanRumah',
                'rumah.pilihanMck',
                'rumah.pilihanListrik',
                'rumah.pilihanSumberAir',
                'rumah.pilihanSumberListrik',
            ])->where('id', $id)->firstOrFail();

            $faktor_jumlah_orang_tinggal = ($data->rumah->jumlah_orang_tinggal <= 3 ? 1 : ($data->rumah->jumlah_orang_tinggal <= 6 ? 2 : 3)) / 3;
            $faktor_luas_tanah = ($data->rumah->luas_tanah <= 90 ? 3 : ($data->rumah->luas_tanah <= 120 ? 2 : 1)) / 3;
            $faktor_luas_bangunan = ($data->rumah->luas_bangunan <= 36 ? 3 : ($data->rumah->luas_bangunan <= 90 ? 2 : 1)) / 3;

            // Faktor pilihan lain (pakai pembalik)
            $faktor_sumber_listrik = pembalik($data->rumah->pilihanSumberListrik->nilai, $jumlahPilihan['sumber_listrik']);
            $faktor_sumber_air = pembalik($data->rumah->pilihanSumberAir->nilai, $jumlahPilihan['sumber_air']);
            $faktor_mck = pembalik($data->rumah->pilihanMck->nilai, $jumlahPilihan['mck']);
            $faktor_listrik = pembalik($data->rumah->pilihanListrik->nilai, $jumlahPilihan['listrik']);
            $faktor_kepemilikan_rumah = pembalik($data->rumah->pilihanKepemilikanRumah->nilai, $jumlahPilihan['kepemilikan_rumah']);

            // Hitung skor (tanpa faktor verifikasi dulu)
            $skor = 0;
            $skor += $faktor_luas_tanah * 0.15;
            $skor += $faktor_luas_bangunan * 0.15;
            $skor += $faktor_kepemilikan_rumah * 0.15;
            $skor += $faktor_jumlah_orang_tinggal * 0.10;
            $skor += $faktor_sumber_listrik * 0.15;
            $skor += $faktor_sumber_air * 0.05;
            $skor += $faktor_mck * 0.05;
            $skor += $faktor_listrik * 0.20;
            $skor_akhir = round($skor * 100, 2); // Skala 0-100

            $data->skor_akhir = $skor_akhir;

            $respon_data = new DataKondisiRumahResource($data, $jumlahPilihan);

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
    public function update(RumahRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();

            $data = Rumah::where('id', $id)->firstOrFail();
            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }

            if ($request->hasFile('foto_rumah')) {
                // Hapus file lama jika ada
                if ($data->foto_rumah && Storage::disk('public')->exists($data->foto_rumah)) {
                    Storage::disk('public')->delete($data->foto_rumah);
                }
                $data_save['foto_rumah'] = upload($request->file('foto_rumah'), 'foto_rumah');
                if (!$data_save['foto_rumah']) {
                    throw new \Exception('Gagal mengunggah foto rumah');
                }
            }

            $data->update($data_save);

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
            $data = Rumah::where('id', $id)->firstOrFail();
            //validasi kepemilikan user
            if ($data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }

            $path = $data->foto_rumah;
            $data->delete();

            DB::commit();
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }
}
