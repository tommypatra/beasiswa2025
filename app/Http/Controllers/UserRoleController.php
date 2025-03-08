<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Requests\UserRoleRequest;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller
{
    public function store(UserRoleRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = UserRole::create($request->validated());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $data], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $data = UserRole::findOrFail($id);
            $data->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat menghapus: ' . $e->getMessage()], 500);
        }
    }

    public function getUserRole($user_id)
    {
        try {
            $roles = Role::all(); // Mengambil semua role
            $user = User::where('id', $user_id)->firstOrFail();
            $user_role = UserRole::where("user_id", $user_id)->get(); // Mengambil user_role berdasarkan user_id
            $akses = [];
            foreach ($roles as $index_roles => $data_role) {
                $akses[$index_roles] = $data_role;
                $akses[$index_roles]['role_user'] = null;
                foreach ($user_role as $index_user_role => $data_user_role) {
                    if ($data_role->id === $data_user_role->role_id) {
                        $akses[$index_roles]['role_user'] = $data_user_role;
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => [
                    'user' => $user,
                    'user_role' => $akses,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    public function cekAkses()
    {
        try {
            return response()->json([
                'user_id' => auth()->user()->id,
                'status' => true,
                'message' => 'Akses diterima',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak : ' . $e->getMessage()], 403);
        }
    }
}
