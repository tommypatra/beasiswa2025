<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\UserRole;
use App\Models\Identitas;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\DB;
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

    function nextLogin($user_id)
    {
        $user = User::with('identitas')->where('id', $user_id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'data'    => null,
                'message'   => "Login gagal, user atau password tidak ditemukan"
            ], 401);
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

        return response()->json($respon_data, 200);
    }

    function cekDataAkunSia(Request $request)
    {
        try {
            DB::beginTransaction();

            $tmp_email = ($request->nim) ? $request->nim : $request->nip;

            $email = ($request->email != '') ? $request->email : $tmp_email . '@iainkendari.ac.id';
            // $nim = $request->nim . '@iainkendari.ac.id';
            if (!$email) {
                throw new \Exception("Gagal, Email pada akun anda di SIA wajib terisi");
            }

            if ($request->grup == 'mahasiswa')
                $user = User::with(['identitas', 'mahasiswa'])
                    ->whereHas('mahasiswa', function ($q) use ($request) {
                        $q->where('nim', $request->nim);
                    })
                    ->first();
            else {
                $user = User::with(['identitas', 'pegawai'])
                    ->whereHas('pegawai', function ($q) use ($request) {
                        $q->where('nip', $request->nip);
                    })
                    ->first();
            }

            // dd($user->id);
            if (!$user) {
                // 1. Buat User
                $user = User::create([
                    'name'     => $request->nama,
                    'email'    => $email,
                    'password' => bcrypt('12345678'),
                ]);

                // 2. Buat Identitas
                Identitas::create([
                    'tempat_lahir'  => $request->tmplahir ?? null,
                    'tanggal_lahir' => $request->tgllahir ?? null,
                    'jenis_kelamin' => $request->jenis_kelamin ?? null,
                    'no_hp'   => $request->hp ?? null,
                    'alamat'  => $request->alamat ?? null,
                    'user_id' => $user->id,
                    'foto' => 'images/user-avatar.png',
                ]);

                // 3. Mahasiswa
                if ($request->grup == 'mahasiswa') {
                    $prodi = ProgramStudi::where('idprodi', $request->idprodi)->first();
                    if (!$prodi) {
                        throw new \Exception("Program studi dengan kode {$request->idprodi} tidak ditemukan");
                    }

                    $tahun_masuk = $request->filled('thnmasuk') ? substr($request->thnmasuk, 0, 4) : null;


                    try {
                        Mahasiswa::create([
                            'nim'              => $request->nim ?? null,
                            'tahun_masuk'      => $tahun_masuk,
                            'ukt'              => $request->ukt ?? null,
                            'sumber_biaya_id'  => 7,
                            'program_studi_id' => $prodi->id,
                            'kartu_mahasiswa'  => 'images/kartumhs.png',
                            'user_id'          => $user->id,
                        ]);
                    } catch (\Illuminate\Database\QueryException $e) {
                        if ($e->getCode() == 23000) {
                            throw new \Exception("NIM {$request->nim} sudah terdaftar. Silakan hubungi admin untuk membantu mereset akun email anda.");
                        }
                        throw $e;
                    }

                    // Role mahasiswa
                    UserRole::create([
                        'role_id' => 2,
                        'user_id' => $user->id,
                    ]);

                    // 4. Pegawai
                } elseif ($request->grup == 'pegawai') {

                    try {
                        Pegawai::create([
                            'user_id'          => $user->id,
                            'nip'              => $request->nip ?? null,
                        ]);
                    } catch (\Illuminate\Database\QueryException $e) {
                        if ($e->getCode() == 23000) {
                            throw new \Exception("NIP {$request->nip} sudah terdaftar. Silakan hubungi admin untuk membantu mereset akun email anda.");
                        }
                        throw $e;
                    }

                    if ($request->sebagai == 'dosen')
                        foreach ([5] as $roleId) {
                            UserRole::create([
                                'role_id' => $roleId,
                                'user_id' => $user->id,
                            ]);
                        }
                    else
                        foreach ([6] as $roleId) {
                            UserRole::create([
                                'role_id' => $roleId,
                                'user_id' => $user->id,
                            ]);
                        }
                }
            }
            DB::commit();
            // DB::rollBack();

            // 5. Login setelah semua data aman
            return $this->nextLogin($user->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat proses login: ' . $e->getMessage()
            ], 500);
        }
    }
}
