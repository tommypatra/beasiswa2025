<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rumah;
use App\Models\OrangTua;
use App\Models\UserRole;
use App\Models\Identitas;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\PendidikanAkhir;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\MahasiswaRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MahasiswaResource;
use App\Http\Requests\PendaftaranMahasiswaRequest;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dataQuery = Mahasiswa::with(['programstudi.fakultas', 'user', 'user.identitas'])->orderBy('program_studi_id', 'asc')->orderBy('nim', 'asc');

        if ($request->filled('search')) {
            $dataQuery->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('user_id')) {
            $user_id = $request->user_id;
            $dataQuery->where('user_id', $user_id);
        }

        $default_limit = env('DEFAULT_LIMIT', 30);
        $limit = $request->filled('limit') ? $request->limit : $default_limit;
        $data = $dataQuery->paginate($limit);
        $resourceCollection = $data->getCollection()->map(function ($item) {
            return new MahasiswaResource($item);
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
    public function store(MahasiswaRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();
            $data_save['user_id'] = auth()->user()->id;
            $data_save['kartu_mahasiswa'] = upload($request->file('kartu_mahasiswa'), 'kartu_mahasiswa');
            $data = Mahasiswa::create($data_save);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($data_save['kartu_mahasiswa'] && Storage::disk('public')->exists($data_save['kartu_mahasiswa'])) {
                Storage::disk('public')->delete($data_save['kartu_mahasiswa']);
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
            $dataQuery = Mahasiswa::with(['user', 'user.identitas'])->where('id', $id)->firstOrFail();
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => new MahasiswaResource($dataQuery),
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
    public function update(MahasiswaRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = Mahasiswa::where('id', $id)->firstOrFail();
            //validasi kepemilikan user
            if (!izinkanAkses("Admin") &&  $data->user_id !== auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'akses anda ditolak'], 403);
            }

            $data_save = $request->validated();

            if ($request->hasFile('kartu_mahasiswa')) {
                // Hapus file lama jika ada
                if ($data->kartu_mahasiswa && Storage::disk('public')->exists($data->kartu_mahasiswa)) {
                    Storage::disk('public')->delete($data->kartu_mahasiswa);
                }
                $data_save['kartu_mahasiswa'] = upload($request->file('kartu_mahasiswa'), 'kartu_mahasiswa');
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
            $data = Mahasiswa::where('id', $id)->firstOrFail();
            //validasi kepemilikan user

            if (!izinkanAkses("Admin") && $data->user_id !== auth()->user()->id) {
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


    public function simpanPendaftaranMahasiswa(PendaftaranMahasiswaRequest $request)
    {
        try {
            DB::beginTransaction();
            $data_save = $request->validated();

            //user
            $respon['user'] = User::create($data_save);
            $data_save['user_id'] = $respon['user']->id;

            //user role
            $data_user_role = [
                'user_id' => $respon['user']->id,
                'role_id' => 2,
            ];
            $respon['user_role'] = UserRole::create($data_user_role);

            //sekolah
            $respon['sekolah'] = PendidikanAkhir::create($data_save);

            //sekolah
            $respon['rumah'] = Rumah::create($data_save);

            //sekolah
            $respon['orang_tua'] = OrangTua::create($data_save);

            //identitas
            $data_save['foto'] = upload($request->file('foto'), 'foto');
            $respon['identitas'] = Identitas::create($data_save);

            //mahasiswa
            $data_save['kartu_mahasiswa'] = upload($request->file('kartu_mahasiswa'), 'kartu_mahasiswa');
            $respon['mahasiswa'] = Mahasiswa::create($data_save);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'data baru berhasil dibuat', 'data' => $respon], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($data_save['kartu_mahasiswa'] && Storage::disk('public')->exists($data_save['kartu_mahasiswa'])) {
                Storage::disk('public')->delete($data_save['kartu_mahasiswa']);
            }
            if ($data_save['foto'] && Storage::disk('public')->exists($data_save['foto'])) {
                Storage::disk('public')->delete($data_save['foto']);
            }
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function cekNim(Request $request)
    {
        try {
            $data = Mahasiswa::with(['user'])->where('nim', $request->nim)->first();

            if ($data) {
                return response()->json([
                    'status' => true,
                    'message' => 'sudah terdaftar untuk ' . $data->user->email . ' atas nama ' . $data->user->name,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Nim tidak ditemukan'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
