<?php

namespace App\Http\Controllers;

use App\Models\Identitas;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\ProgramStudi;
use App\Models\User;
use App\Models\UserRole;
use App\Services\SevimaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class AuthSevimaController extends Controller
{
    public function __construct(protected SevimaService $sevima)
    {
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);

        $response = $this->sevima->post('/siakadcloud/v1/user/login', ['email' => $validated['email'], 'password' => $validated['password']]);

        if (!$response['success']) {
            return $this->sevimaLoginFailed($response);
        }

        $attributes = $response['data']['attributes'] ?? null;

        if (!is_array($attributes)) {
            return $this->errorResponse('Data akun SIAKAD tidak valid.', 422);
        }

        if ((string) ($attributes['status_aktif'] ?? '0') !== '1') {
            return $this->errorResponse('Akun SIAKAD Anda tidak aktif.', 403);
        }

        $roles = $attributes['role'] ?? [];

        if (!is_array($roles) || empty($roles)) {
            return $this->errorResponse('Role akun SIAKAD tidak ditemukan.', 403);
        }

        $jenisAkun = $this->detectAccountType($roles);

        if (!$jenisAkun) {
            return $this->errorResponse('Jenis akun SIAKAD Anda belum didukung oleh sistem ini.', 403);
        }

        try {
            $result = DB::transaction(function () use ($attributes, $roles, $jenisAkun) {
                return $jenisAkun === 'mahasiswa' ? $this->syncMahasiswa($attributes, $roles) : $this->syncPegawai($attributes, $roles, $jenisAkun);
            });
        } catch (Throwable $e) {
            report($e);
            return $this->syncFailedResponse($e);
        }

        try {
            $token = auth()->guard('api')->login($result['user']);
        } catch (Throwable $e) {
            report($e);
            return $this->errorResponse('Data berhasil disinkronkan, tetapi sesi login gagal dibuat.', 500);
        }

        $daftarAkses = daftarAkses($result['user']->id);

        if (empty($daftarAkses)) {
            return $this->errorResponse('Akses akun belum tersedia.', 403);
        }

        $foto = Identitas::where('user_id', $result['user']->id)->value('foto') ?: 'images/user-avatar.png';

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user_id' => $result['user']->id,
                'user_name' => $result['user']->name,
                'user_email' => $result['user']->email,
                'access_token' => $token,
                'foto' => $foto,
                'daftar_akses' => $daftarAkses,
                'akses' => collect($daftarAkses)->min('role_id'),
            ],
        ]);
    }

    protected function detectAccountType(array $roles): ?string
    {
        $roleIds = collect($roles)->pluck('id_role')->filter()->map(fn ($role) => strtolower(trim((string) $role)));

        if ($roleIds->contains('mhs')) {
            return 'mahasiswa';
        }

        if ($roleIds->contains('dosen')) {
            return 'dosen';
        }

        if ($roleIds->contains('peg')) {
            return 'pegawai';
        }

        return null;
    }

    protected function syncMahasiswa(array $attributes, array $roles): array
    {
        $email = trim((string) ($attributes['email'] ?? ''));
        $nama = trim((string) ($attributes['nama'] ?? '')) ?: 'Mahasiswa';

        if (!$email) {
            throw new \RuntimeException('Email akun SIAKAD tidak ditemukan.');
        }

        $mhsRoles = collect($roles)->filter(fn ($role) => strtolower(trim((string) ($role['id_role'] ?? ''))) === 'mhs')->values();

        if ($mhsRoles->isEmpty()) {
            throw new \RuntimeException('Data mahasiswa tidak ditemukan dari SIAKAD.');
        }

        $userIds = [];
        $dataMahasiswa = [];

        foreach ($mhsRoles as $role) {
            $nim = trim((string) ($role['nim'] ?? ''));
            $sevimaProdiId = trim((string) ($role['id_satker'] ?? ''));
            $periodeMasuk = trim((string) ($role['periode_masuk'] ?? ''));

            if (!$nim) {
                throw new \RuntimeException('NIM mahasiswa tidak ditemukan dari SIAKAD.');
            }

            if (!$sevimaProdiId) {
                throw new \RuntimeException("Prodi untuk NIM {$nim} tidak ditemukan dari SIAKAD.");
            }

            $prodi = ProgramStudi::where('sevima_prodi_id', $sevimaProdiId)->first();

            if (!$prodi) {
                throw new \RuntimeException('Prodi Anda tidak didukung oleh sistem ini.');
            }

            $mahasiswa = Mahasiswa::where('nim', $nim)->first();

            if ($mahasiswa?->user_id) {
                $userIds[] = (int) $mahasiswa->user_id;
            }

            $dataMahasiswa[] = [
                'nim' => $nim,
                'periode_masuk' => $periodeMasuk ?: null,
                'prodi' => $prodi,
                'mahasiswa' => $mahasiswa,
            ];
        }

        $userIds = array_values(array_unique($userIds));

        if (count($userIds) > 1) {
            throw new \RuntimeException('Data NIM Anda terhubung ke akun yang berbeda. Silakan hubungi pengelola.');
        }

        $user = !empty($userIds) ? User::find($userIds[0]) : User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make(Str::random(64)),
            ]);
        } else {
            $this->syncUser($user, $nama, $email);
        }

        foreach ($dataMahasiswa as $item) {
            $this->syncSingleMahasiswa($user, $item['nim'], $item['periode_masuk'], $item['prodi']);
        }

        $this->ensureIdentitas($user);
        $this->syncApplicationRoles($user->id, 'mahasiswa');

        return ['user' => $user->fresh(), 'jenis_akun' => 'mahasiswa'];
    }

    protected function syncSingleMahasiswa(User $user, string $nim, ?string $periodeMasuk, ProgramStudi $prodi): Mahasiswa
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            if ($mahasiswa->user_id && (int) $mahasiswa->user_id !== (int) $user->id) {
                throw new \RuntimeException("NIM {$nim} sudah terhubung dengan akun lain.");
            }

            $mahasiswa->update([
                'user_id' => $user->id,
                'program_studi_id' => $prodi->id,
                'tahun_masuk' => $periodeMasuk ? (int) substr($periodeMasuk, 0, 4) : $mahasiswa->tahun_masuk,
            ]);

            if (!$mahasiswa->kartu_mahasiswa) {
                $mahasiswa->update(['kartu_mahasiswa' => 'images/kartumhs.png']);
            }

            return $mahasiswa->fresh();
        }

        return Mahasiswa::create([
            'nim' => $nim,
            'kartu_mahasiswa' => 'images/kartumhs.png',
            'tahun_masuk' => $periodeMasuk ? (int) substr($periodeMasuk, 0, 4) : null,
            'program_studi_id' => $prodi->id,
            'user_id' => $user->id,
        ]);
    }

    protected function syncPegawai(array $attributes, array $roles, string $jenisAkun): array
    {
        $email = trim((string) ($attributes['email'] ?? ''));
        $nama = trim((string) ($attributes['nama'] ?? '')) ?: ($jenisAkun === 'dosen' ? 'Dosen' : 'Pegawai');

        if (!$email) {
            throw new \RuntimeException('Email akun SIAKAD tidak ditemukan.');
        }

        $targetRole = $jenisAkun === 'dosen' ? 'dosen' : 'peg';

        $roleUtama = collect($roles)->first(fn ($role) => strtolower(trim((string) ($role['id_role'] ?? ''))) === $targetRole);

        if (!$roleUtama) {
            throw new \RuntimeException($jenisAkun === 'dosen' ? 'Data dosen tidak ditemukan dari SIAKAD.' : 'Data pegawai tidak ditemukan dari SIAKAD.');
        }

        $nip = trim((string) ($roleUtama['nip'] ?? ''));

        if (!$nip) {
            throw new \RuntimeException('NIP tidak ditemukan dari SIAKAD.');
        }

        $pegawai = Pegawai::where('nip', $nip)->first();

        if ($pegawai?->user_id) {
            $user = User::find($pegawai->user_id);

            if (!$user) {
                throw new \RuntimeException("User untuk NIP {$nip} tidak ditemukan.");
            }

            $this->syncUser($user, $nama, $email);
        } else {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make(Str::random(64)),
                ]);
            } else {
                $this->syncUser($user, $nama, $email);
            }
        }

        if ($pegawai) {
            if ($pegawai->user_id && (int) $pegawai->user_id !== (int) $user->id) {
                throw new \RuntimeException("NIP {$nip} sudah terhubung dengan akun lain.");
            }

            $pegawai->update(['user_id' => $user->id]);
        } else {
            Pegawai::create([
                'nip' => $nip,
                'user_id' => $user->id,
            ]);
        }

        $this->ensureIdentitas($user);
        $this->syncApplicationRoles($user->id, $jenisAkun);

        return ['user' => $user->fresh(), 'jenis_akun' => $jenisAkun];
    }

    protected function syncUser(User $user, string $nama, string $email): void
    {
        $emailUser = User::where('email', $email)->where('id', '!=', $user->id)->first();

        if ($emailUser) {
            throw new \RuntimeException("Email SIAKAD {$email} sudah terhubung dengan akun lain.");
        }

        $user->update([
            'name' => $nama,
            'email' => $email,
        ]);
    }

    protected function ensureIdentitas(User $user): Identitas
    {
        return Identitas::firstOrCreate(['user_id' => $user->id], [
            'tempat_lahir' => null,
            'inisial' => null,
            'tanggal_lahir' => null,
            'jenis_kelamin' => null,
            'no_hp' => null,
            'foto' => 'images/user-avatar.png',
            'alamat' => null,
            'desa' => null,
            'kecamatan' => null,
            'kabupaten' => null,
            'provinsi' => null,
            'wilayah_desa_id' => null,
        ]);
    }

protected function syncApplicationRoles(int $userId, string $jenisAkun): void
{
    $roles = match ($jenisAkun) {
        'mahasiswa' => ['Mahasiswa'],
        'dosen' => ['Pewawancara'],
        'pegawai' => ['Surveyor'],
        default => [],
    };

    if (empty($roles)) {
        throw new \RuntimeException('Kategori akun tidak valid.');
    }

    if (UserRole::where('user_id', $userId)->exists()) {
        return;
    }

    $roleIds = DB::table('roles')->whereIn('nama', $roles)->pluck('id')->toArray();

    if (count($roleIds) !== count($roles)) {
        throw new \RuntimeException('Role aplikasi belum lengkap di database.');
    }

    foreach ($roleIds as $roleId) {
        UserRole::firstOrCreate(['user_id' => $userId, 'role_id' => $roleId]);
    }
}

    protected function sevimaLoginFailed(array $response): JsonResponse
    {
        $status = (int) ($response['status'] ?? 500);
        $message = strtolower((string) ($response['message'] ?? ''));

        if (in_array($status, [401, 422])) {
            return $this->errorResponse('Email atau password SIAKAD salah.', 401);
        }

        if ($status === 403 && str_contains($message, 'whitelist')) {
            return $this->errorResponse('Layanan SIAKAD tidak dapat diakses dari server ini.', 403);
        }

        if ($status === 403) {
            return $this->errorResponse('Akses ke layanan SIAKAD ditolak.', 403);
        }

        if ($status === 429) {
            return $this->errorResponse('Layanan SIAKAD sedang sibuk. Silakan coba beberapa saat lagi.', 429);
        }

        if ($status >= 500) {
            return $this->errorResponse('Layanan SIAKAD sedang mengalami gangguan.', 500);
        }

        return $this->errorResponse('Login SIAKAD gagal.', 500);
    }

    protected function syncFailedResponse(Throwable $e): JsonResponse
    {
        report($e);

        return $this->errorResponse('Data akun belum dapat disinkronkan. Silakan coba login kembali.', 500);
    }

    protected function errorResponse(string $message, int $status): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
        ], $status);
    }
}