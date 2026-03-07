<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Beasiswa;
// use App\Models\UserRole;
use App\Models\Mahasiswa;
use App\Models\Pendaftar;
use App\Models\JadwalUjian;
use App\Models\Pewawancara;
use App\Models\Verifikator;
use App\Models\WilayahDesa;
use Illuminate\Support\Str;
use App\Models\AdminSeleksi;
use App\Models\PesertaUjian;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
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

function upload(?UploadedFile $file, string $folder): ?string
{
    if (!$file || !$file->isValid()) {
        return null;
    }

    try {

        $allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'];
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, $allowedExt)) {
            return null;
        }

        $allowedMime = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        if (!in_array($file->getMimeType(), $allowedMime)) {
            return null;
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            return null;
        }

        $userId = auth()->check() ? auth()->id() : 'guest';

        $namaFile = $userId . '_' . time() . '_' . uniqid() . '.' . $ext;

        $path_dokumen = $folder . '/' . date('Y');

        if (!Storage::disk('public')->exists($path_dokumen)) {
            Storage::disk('public')->makeDirectory($path_dokumen);
        }

        $path = $file->storeAs($path_dokumen, $namaFile, 'public');

        return $path && Storage::disk('public')->exists($path) ? 'storage/' . $path : null;
    } catch (\Throwable $e) {
        return null;
    }
}


if (!function_exists('dataWilayah')) {
    function dataWilayah($desa_id)
    {
        $dataWilayah = [
            'desa' => null,
            'kecamatan' => null,
            'kabupaten' => null,
            'provinsi' => null,
        ];
        try {
            $data = WilayahDesa::with([
                'wilayahKecamatan.wilayahKabupaten.wilayahProvinsi',
            ])->where('id', $desa_id)->firstOrFail();

            $dataWilayah = [
                'desa' => $data->desa,
                'kecamatan' => $data->wilayahKecamatan->nama,
                'kabupaten' => $data->wilayahKecamatan->wilayahKabupaten->nama,
                'provinsi' => $data->wilayahKecamatan->wilayahKabupaten->wilayahProvinsi->nama,
            ];

            return $dataWilayah;
        } catch (\Exception $e) {
            return $dataWilayah;
        }
    }
}

if (!function_exists('cariVerifikatorBerkas')) {
    function cariVerifikatorBerkas($beasiswa_id)
    {
        $verifikator = Verifikator::withCount([
            'verifikatorPendaftar as total_peserta' => function ($query) {
                $query->select(DB::raw('count(distinct pendaftar_id)'));
            }
        ])
            ->where('beasiswa_id', $beasiswa_id)
            ->orderBy('total_peserta', 'asc') // paling sedikit dulu
            ->orderBy('id', 'asc') // kalau sama, ambil id terkecil
            ->first();

        return $verifikator?->id;
    }
}

if (!function_exists('cariPewawancara')) {
    function cariPewawancara($beasiswa_id)
    {
        $pewawancara = Pewawancara::withCount([
            'pesertaWawancara as total_peserta' => function ($query) {
                $query->select(DB::raw('count(distinct pendaftar_id)'));
            }
        ])
            ->where('beasiswa_id', $beasiswa_id)
            ->orderBy('total_peserta', 'asc') // paling sedikit dulu
            ->orderBy('id', 'asc') // kalau sama, ambil id terkecil
            ->first();

        return $pewawancara?->id;
    }
}

if (!function_exists('skorAkreditasi')) {
    function skorAkreditasi($akreditasi)
    {
        $akreditasiMap = [
            'A' => env('AKREDITASI_A', 100),
            'B' => env('AKREDITASI_B', 80),
            'C' => env('AKREDITASI_C', 60),
        ];
        $skorAkredit = env('AKREDITASI_NONE', 30);
        if (isset($akreditasiMap[$akreditasi])) {
            $skorAkredit = $akreditasiMap[$akreditasi];
        }
        return $skorAkredit;
    }
}

if (!function_exists('pembalik')) {
    function pembalik($nilai, $totalPilihan)
    {
        return ($totalPilihan - $nilai + 1) / $totalPilihan;
    }
}


if (!function_exists('validasiPendaftaran')) {
    function validasiPendaftaran($beasiswa_id)
    {
        $user_id = auth()->id();
        $user = User::with(["identitas", "nilaiRaport", "orangTua", "rumah", "mahasiswa", "pendidikanAkhir"])->where("id", $user_id)->first();

        $beasiswa = Beasiswa::where('id', $beasiswa_id)->first();

        $data['user'] = $user;
        $data['identitas'] = optional($user->identitas)->wilayah_desa_id ? true : false;
        $data['data_mahasiswa'] = optional($user->mahasiswa)->id ? true : false;
        $data['angkatan_mahasiswa'] = true;
        $data['lulus_sma'] = true;

        $data['nilai_raport'] = true;
        $data['orang_tua'] = true;
        $data['rumah'] = true;
        $data['pendidikan_akhir'] = true;

        if ($beasiswa->perlu_data_orang_tua) {
            $data['orang_tua'] = ($user->orangTua && ($user->orangTua->tanggungan >= 0));
        }

        if ($beasiswa->perlu_data_nilai_raport) {
            $data['nilai_raport'] = (bool) $user->nilaiRaport;
            // $data['nilai_raport'] = (
            //     $user->nilaiRaport && (
            //         !empty($user->nilaiRaport->foto_raport_smt_1) &&
            //         !empty($user->nilaiRaport->foto_raport_smt_2) &&
            //         !empty($user->nilaiRaport->foto_raport_smt_3) &&
            //         !empty($user->nilaiRaport->foto_raport_smt_4) &&
            //         !empty($user->nilaiRaport->foto_raport_smt_5) &&
            //         !empty($user->nilaiRaport->foto_raport_smt_6)
            //     )
            // ) ? true : false;
        }


        if ($beasiswa->perlu_data_rumah) {
            $data['rumah'] = (bool) $user->rumah;
            // $data['rumah'] = ($user->rumah && !empty($user->rumah->foto_rumah));
        }
        if ($beasiswa->perlu_data_pendidikan_akhir) {
            $data['pendidikan_akhir'] = (bool) $user->pendidikanAkhir;
            // $data['pendidikan_akhir'] = ($user->pendidikanAkhir && !empty($user->pendidikanAkhir->foto_ijazah));
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

        $data['ukt_memenuhi'] = true;

        if ($beasiswa->nilai_minimal_ukt) {
            $data['ukt_memenuhi'] = false;
            if ($user->mahasiswa && $user->mahasiswa->ukt !== null) {
                $data['ukt_memenuhi'] = ($user->mahasiswa->ukt > $beasiswa->nilai_minimal_ukt) ? true : false;
            }
        }
        // echo "UKT MEMENUHI : " . $beasiswa->nilai_minimal_ukt . " " . $data['ukt_memenuhi'];
        // die;


        $data['sudah_mendaftar'] = Pendaftar::with(['kelulusan', 'verifikatorPendaftar'])->whereHas('beasiswa', fn($q) => $q->where('is_aktif', '1')->where('tahun', $beasiswa->tahun))
            ->whereHas('mahasiswa', fn($q) => $q->where('user_id', $user_id))
            ->where(function ($query) {
                $query->whereHas('kelulusan', function ($q) {
                    $q->where('is_lulus', 1);
                })->orWhereDoesntHave('kelulusan');
            })->where(function ($query) {
                $query->whereHas('verifikatorPendaftar', function ($q) {
                    $q->where('hasil', 1);
                })->orWhereDoesntHave('verifikatorPendaftar');
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

if (!function_exists('adminSeleksi')) {
    function adminSeleksi($beasiswa_id = null)
    {
        $user = auth()->user();
        $isAdminSeleksi = AdminSeleksi::where([
            'user_id' => $user->id,
            'beasiswa_id' => $beasiswa_id
        ])->exists();

        return ($isAdminSeleksi) ? true : false;
    }
}

if (!function_exists('izinkanAkses')) {
    function izinkanAkses($grup = "global")
    {
        $user = auth()->user();
        if ($grup == "global") {
            return true;
        }

        $daftar_grup = daftarAkses($user->id);
        if (count($daftar_grup) == 0) {
            return false;
        }

        // kalau $grup berupa array
        if (is_array($grup)) {
            foreach ($daftar_grup as $dt) {
                if (in_array(strtolower($dt->role), array_map('strtolower', $grup))) {
                    return true;
                }
            }
        } else {
            // kalau $grup single string
            foreach ($daftar_grup as $dt) {
                if (strtolower($grup) == strtolower($dt->role)) {
                    return true;
                }
            }
        }
        return false;
    }

    // function izinkanAkses($grup = "global")
    // {
    //     if ($grup != "global") {
    //         $user = auth()->user();
    //         $daftar_grup = daftarAkses($user->id);
    //         if (count($daftar_grup) > 0)
    //             foreach ($daftar_grup as $i => $dt) {
    //                 if (strtolower($grup) == strtolower($dt->role)) {
    //                     return true;
    //                 }
    //             }
    //         return false;
    //     }
    //     return true;
    // }
}


function isMobileDev()
{
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $user_ag = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis', $user_ag)) {
            return true;
        };
    };
    return false;
}

if (!function_exists('kirimwa')) {
    function kirimwa($hp = "", $pesan = '')
    {
        $retval = "";
        if ($hp <> "") {
            $tmphp = explode("/", $hp);
            foreach ($tmphp as $hp) {
                $hp = trim($hp);
                $hp = str_replace(array("-", " "), array("", ""), $hp);
                $hp = preg_replace("/[^a-zA-Z0-9\s]/", "", $hp);

                if (substr($hp, 0, 1) == "0")
                    $hp = "+62" . substr($hp, 1, strlen($hp));

                if (isMobileDev())
                    $retval = $retval . "<a href='https://wa.me/" . $hp . "?text=" . $pesan . "' target='_blank'>" . $hp . "</a> ";
                else
                    $retval = $retval . "<a href='https://web.whatsapp.com/send?phone=" . $hp . "&text=" . $pesan . "' target='_blank'>" . $hp . "</a> ";
            }
        }
        return $retval;
    }
}

if (!function_exists('linkwa')) {
    function linkwa($hp = "", $pesan = '')
    {
        $retval = "";
        if ($hp <> "") {
            $tmphp = explode("/", $hp);
            foreach ($tmphp as $hp) {
                $hp = trim($hp);
                $hp = str_replace(array("-", " "), array("", ""), $hp);
                $hp = preg_replace("/[^a-zA-Z0-9\s]/", "", $hp);

                if (substr($hp, 0, 1) == "0")
                    $hp = "+62" . substr($hp, 1, strlen($hp));

                if (isMobileDev())
                    $retval = "https://wa.me/" . $hp . "?text=";
                else
                    $retval = "https://web.whatsapp.com/send?phone=" . $hp . "&text=";
            }
        }
        return $retval;
    }
}

if (!function_exists('tambahJadwalUjian')) {
    function tambahJadwalUjian($beasiswa_id, $pendaftar_id)
    {
        DB::beginTransaction();

        try {
            // 1. Lock peserta agar tidak dobel assign
            $sudahAda = PesertaUjian::where('pendaftar_id', $pendaftar_id)
                ->whereHas(
                    'jadwalUjian',
                    fn($q) =>
                    $q->where('beasiswa_id', $beasiswa_id)
                )
                ->lockForUpdate()
                ->exists();
            if ($sudahAda) {
                DB::rollBack();
                return [
                    'status'  => false,
                    'code'    => 400,
                    'message' => 'Peserta ini sudah memiliki jadwal ujian untuk beasiswa tersebut',
                    'data'    => null,
                ];
            }
            // 2. Ambil jadwal + hitung peserta langsung
            $jadwals = JadwalUjian::with([
                'ruanganUjian:id,jumlah_peserta'
            ])
                ->withCount('pesertaUjian')
                ->where('beasiswa_id', $beasiswa_id)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($jadwals->isEmpty()) {
                DB::rollBack();
                return [
                    'status'  => false,
                    'code'    => 400,
                    'message' => 'Jadwal ujian belum digenerate pada seleksi ini',
                    'data'    => null,
                ];
            }
            // 3. Cari jadwal yang masih ada slot
            foreach ($jadwals as $jadwal) {
                $ruang = $jadwal->ruanganUjian;
                if (! $ruang) {
                    continue;
                }
                if ($jadwal->peserta_ujian_count < (int) $ruang->jumlah_peserta) {
                    $pesertaUjian = PesertaUjian::create([
                        'pendaftar_id'    => $pendaftar_id,
                        'jadwal_ujian_id' => $jadwal->id,
                    ]);
                    DB::commit();
                    return [
                        'status'  => true,
                        'code'    => 201,
                        'message' => 'Peserta berhasil ditempatkan ke jadwal ujian.',
                        'data'    => $pesertaUjian,
                    ];
                }
            }
            DB::rollBack();
            return [
                'status'  => false,
                'code'    => 400,
                'message' => 'Semua jadwal ujian untuk beasiswa ini sudah penuh.',
                'data'    => null,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'status'  => false,
                'code'    => 500,
                'message' => 'Gagal menambah peserta: ' . $e->getMessage(),
                'data'    => null,
            ];
        }
    }
}
