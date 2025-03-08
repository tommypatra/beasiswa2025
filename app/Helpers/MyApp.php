<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Beasiswa;
use App\Models\UserRole;
use App\Models\Mahasiswa;
use App\Models\Pendaftar;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

if (!function_exists('daftarAkses')) {
    function daftarAkses($user_id)
    {
        $listAkses = [];
        $getUser = User::with(['userRole.role'])->where('id', $user_id)->first();
        if (is_null($getUser)) {
            return [];
        }

        foreach ($getUser->userRole as $i => $dt) {
            $listAkses[] = ['user_role_id' => $dt->id, 'user_id' => $dt->user_id, 'role' => $dt->role->nama, 'role_id' => $dt->role_id];
        }
        return json_decode(json_encode($listAkses));
    }
}

if (!function_exists('upload')) {
    function upload($file, $folder)
    {
        $namaFile = time() . '_' . $file->getClientOriginalName(); // Nama unik
        $path_dokumen = $folder . '/' . date('Y');
        if (!Storage::disk('public')->exists($path_dokumen)) {
            Storage::disk('public')->makeDirectory($path_dokumen);
        }
        $path = $file->storeAs($path_dokumen, $namaFile, 'public');
        return 'storage/' . $path;
        // return Storage::url($path);
    }
}


if (!function_exists('validasiPendaftaran')) {
    function validasiPendaftaran($beasiswa_id)
    {
        $user_id = auth()->id();
        $user = User::with(["nilaiRaport", "orangTua", "rumah", "mahasiswa", "pendidikanAkhir"])->where("id", $user_id)->first();

        $beasiswa = Beasiswa::where('id', $beasiswa_id)
            ->selectRaw('*, CASE 
                                WHEN NOW() BETWEEN daftar_mulai AND daftar_selesai THEN true 
                            ELSE false 
                            END as is_pendaftaran_aktif')
            ->first();

        $data['user'] = $user;
        $data['angkatan_mahasiswa'] = true;
        $data['lulus_sma'] = true;

        $data['nilai_raport'] = true;
        $data['orang_tua'] = true;
        $data['rumah'] = true;
        $data['pendidikan_akhir'] = true;
        if ($beasiswa->perlu_data_orang_tua) {
            $data['orang_tua'] = ($user->orangTua) ? true : false;
        }
        if ($beasiswa->perlu_data_orang_tua) {
            $data['nilai_raport'] = ($user->nilaiRaport) ? true : false;
        }
        if ($beasiswa->perlu_data_rumah) {
            $data['rumah'] = ($user->rumah) ? true : false;
        }
        if ($beasiswa->perlu_data_rumah) {
            $data['pendidikan_akhir'] = ($user->pendidikanAkhir) ? true : false;
        }

        $tahun_lulus_sma = ($user->pendidikanAkhir) ? $user->pendidikanAkhir->tahun_lulus : "";

        if ($beasiswa->syarat_tahun_lulus_sma) {
            $dataTmp = trim($beasiswa->syarat_tahun_lulus_sma) . ",";
            $data['lulus_sma'] = in_array($tahun_lulus_sma, explode(",", $dataTmp)) ? true : false;
        }

        if ($beasiswa->syarat_tahun_angkatan_mahasiswa) {
            $dataTmp = trim($beasiswa->syarat_tahun_angkatan_mahasiswa) . ",";
            $data['angkatan_mahasiswa'] = in_array($user->mahasiswa->tahun_masuk, explode(",", $dataTmp)) ? true : false;
        }

        $tahun = $beasiswa->tahun;
        $mahasiswa_id = $user->mahasiswa->id;

        $data_pendaftaran = Pendaftar::where('beasiswa_id', $beasiswa_id)
            ->whereHas('mahasiswa', fn($q) => $q->where('user_id', $user_id))
            ->first();

        $data['batal'] = ($data_pendaftaran) ? $data_pendaftaran->is_batal : null;
        $data['finalisasi'] = ($data_pendaftaran) ? $data_pendaftaran->is_finalisasi : null;
        $data['pendaftaran_aktif'] = $beasiswa->is_pendaftaran_aktif;

        $data['sudah_mendaftar'] = Pendaftar::with(['kelulusan'])->whereHas('beasiswa', fn($q) => $q->where('tahun', $beasiswa->tahun))
            ->whereHas('mahasiswa', fn($q) => $q->where('user_id', $user_id))
            ->where(function ($query) {
                $query->whereHas('kelulusan', function ($q) {
                    $q->where('is_lulus', 1);
                })->orWhereDoesntHave('kelulusan');
            })
            ->where("is_batal", "0")
            ->exists();
        // dd($data);
        return (object)$data;
    }
}

if (!function_exists('cariMahasiswa')) {
    function cariMahasiswa()
    {
        return Mahasiswa::where('user_id', auth()->user()->id)->first();
    }
}

if (!function_exists('getEmailsByRoles')) {
    function getEmailsByRoles(array $roleNames)
    {
        return User::with(['userRole.role'])
            ->whereHas('userRole.role', function ($query) use ($roleNames) {
                $query->whereIn('nama', $roleNames);
            })
            ->distinct()
            ->pluck('email');
    }
}


if (!function_exists('cekRole')) {

    function cekRole($daftar_role, $role_name)
    {
        $aksesArray = json_decode(json_encode($daftar_role), true);
        foreach ($aksesArray as $aksesItem) {
            if ($aksesItem['role'] === $role_name) {
                return $aksesItem['user_role_id'];
            }
        }
        return false;
    }
}

if (!function_exists('anchor')) {
    function anchor($url, $text)
    {
        return '<a href="' . $url . '">' . $text . '</a>';
    }
}

if (!function_exists('dbDateTimeFormat')) {
    function dbDateTimeFormat($waktuDb, $format = 'Y-m-d H:i:s')
    {
        return Carbon::parse($waktuDb)->timezone('Asia/Makassar')->format($format);
    }
}

if (!function_exists('generateUniqueFileName')) {
    function generateUniqueFileName()
    {
        return $randomString = time() . Str::random(22);
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($judul, $waktu)
    {
        $disallowed_chars = array(
            '!',
            '@',
            '#',
            '$',
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            '+',
            '=',
            '{',
            '}',
            '[',
            ']',
            '|',
            '\\',
            ';',
            ':',
            '"',
            '<',
            '>',
            ',',
            '.',
            '/',
            '?',
            ' ',
            "'",
            ' '
        );
        $judul = str_replace(' ', '-', $judul);
        $judul = str_replace($disallowed_chars, ' ', $judul);
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $judul));

        $timestamp = strtotime($waktu);

        $tgl = date('y', $timestamp) + date('j', $timestamp) + date('n', $timestamp) + date('w', $timestamp);
        $waktu = date('H', $timestamp) + date('i', $timestamp);
        // $tanggal = date('ymd', strtotime($waktu));
        // $waktu = date('his', strtotime($waktu));
        // $tanggal = date('ymd', strtotime($waktu));
        // $waktu = date('his', strtotime($waktu));

        $generateWaktu = ($tgl + $waktu + rand(1, 999)) . '-' . date('s', $timestamp);
        // $finalSlug = date('ymd', $timestamp) . '-' . $slug . '-' . $generateWaktu;
        $finalSlug = $slug . '-' . $generateWaktu;
        return $finalSlug;
    }
}

if (!function_exists('ukuranFile')) {
    function ukuranFile($size)
    {
        $satuan = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . ' ' . $satuan[$i];
    }
}

if (!function_exists('updateTokenUsed')) {
    function updateTokenUsed()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $token = $user->tokens->last();
            if ($token) {
                $token->forceFill([
                    'created_at' => now(),
                    'last_used_at' => now(),
                ])->save();
            }
        }
    }
}

if (!function_exists('ambilKata')) {
    function ambilKata($text, $limit = 25)
    {
        $text = strip_tags($text);
        $words = preg_split("/[\s,]+/", $text);
        $shortenedText = implode(' ', array_slice($words, 0, $limit));
        if (str_word_count($text) > $limit) {
            $shortenedText .= '...';
        }
        return $shortenedText;
    }
}

if (!function_exists('enkrip')) {
    function enkrip($text)
    {
        $key = Carbon::now()->format('Y-m-d');
        $enc = Crypt::encryptString($text, $key);

        return $enc;
    }
}

if (!function_exists('dekrip')) {
    function dekrip($dectext)
    {
        $key = Carbon::now()->format('Y-m-d');
        $dec = Crypt::decryptString($dectext, $key);
        return $dec;
    }
}


if (!function_exists('izinkanAkses')) {
    function izinkanAkses($grup = "global")
    {
        if ($grup != "global") {
            $user = auth()->user();
            $daftar_grup = daftarAkses($user->id);
            if (count($daftar_grup) > 0)
                foreach ($daftar_grup as $i => $dt) {
                    if (strtolower($grup) == strtolower($dt->role)) {
                        return true;
                    }
                }
            return false;
        }
        return true;
    }
}
