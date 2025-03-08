<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Identitas;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function index(AuthRequest $request)
    {

        $credentials = $request->validated();

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            // if (!$token = auth()->guard('api')->claims([
            //     'email' => $request->input('email')
            // ])->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'data'    => null,
                'message'   => "Login gagal, user atau password tidak ditemukan"
            ], 401);
        }

        $user = auth()->guard('api')->user();

        $profil = Identitas::where("user_id", $user->id)->first();
        $foto = 'images/user-avatar.png';
        if ($profil) {
            $foto = ($profil->foto) ? ($profil->foto) : 'images/user-avatar.png';
        }

        $daftarAkses = daftarAkses($user->id);

        $role_akses = $daftarAkses[0]->role;
        $role_id = $daftarAkses[0]->role_id;

        $respon_data = [
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'access_token' => $token,
                'foto' => $foto,
                'daftar_akses' => $daftarAkses,
                'akses' => $role_id,
            ]
        ];
        return response()->json($respon_data, 200);
    }

    public function roleUser()
    {
        $daftarAkses = daftarAkses(Auth::user()->id);
        if (count($daftarAkses) < 1)
            return response()->json(['status' => false, 'message' => 'akses tidak ditemukan'], 404);
        $role_aksess = $daftarAkses[0]->role;
        $user_role_id = $daftarAkses[0]->user_role_id;
        $respon_data = [
            'status' => true,
            'message' => 'akses ditemukan',
            'data' => $daftarAkses,
        ];
        return response()->json($respon_data, 200);
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $email = $googleUser->getEmail();
            $user = User::with('identitas')->where('email', $email)->first();
            if (!$user) {
                return Redirect::to('login')->with('error', 'Login gagal, silahkan coba lagi');
            }
            $token = auth()->guard('api')->login($user);

            $daftarAkses = daftarAkses($user->id);
            $role_akses = $daftarAkses[0]->role;
            $role_id = $daftarAkses[0]->role_id;

            $foto = 'images/user-avatar.png';
            if ($user['identitas']) {
                $foto = ($user['identitas']->foto) ? ($user['identitas']->foto) : 'images/user-avatar.png';
            }

            $respon_data = [
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'access_token' => $token,
                    'foto' => $foto,
                    'daftar_akses' => $daftarAkses,
                    'akses' => $role_id,
                ]
            ];

            return redirect::to('/login')->with('respon_google_login', $respon_data);
        } catch (\Exception $e) {
            return Redirect::to('login')->with('error', 'Login failed, please try again. ' . $e->getMessage());
        }
    }

    function tokenCek($grup_id)
    {
        $user_id = auth()->check() ? auth()->user()->id : null;
        if ($user_id) {
            $daftar_akses = daftarAkses($user_id);
            $index = array_search($grup_id, array_column($daftar_akses, 'grup_id'));
            if ($index !== false) {
                return response()->json(['status' => true, 'message' => 'token valid'], 200);
            }
        }
        return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $token = auth()->guard('api')->getToken();
        auth()->guard('api')->logout();

        JWTAuth::invalidate($token);
        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil',
            'data' => null,
        ], 200);
    }
}
