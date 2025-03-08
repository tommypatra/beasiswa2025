<?php

use App\Models\Syarat;
use App\Models\Beasiswa;
use App\Models\OrangTua;
use App\Models\Pendaftar;
use App\Models\SumberAir;
use App\Models\NilaiRaport;
use Illuminate\Http\Request;
use App\Models\JenisBeasiswa;
use App\Models\SoalWawancara;
use App\Http\Controllers\WilayahDesa;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\PenggunaRequest;
use App\Http\Controllers\MckController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\WilayahProvinsi;
use App\Http\Controllers\SyaratController;
use App\Http\Controllers\WilayahKabupaten;
use App\Http\Middleware\CekAksesMiddleware;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\IdentitasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\SumberAirController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\NilaiRaportController;
use App\Http\Controllers\SumberBiayaController;
use App\Http\Controllers\WilayahDesaController;
use App\Http\Middleware\JwtAuthenticateRefresh;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\UploadSyaratController;
use App\Http\Controllers\JenisBeasiswaController;
use App\Http\Controllers\SoalWawancaraController;
use App\Http\Controllers\SumberListrikController;
use App\Http\Controllers\StatusOrangTuaController;
use App\Http\Controllers\PendidikanAkhirController;
use App\Http\Controllers\ReferensiPilihanController;
use App\Http\Controllers\WilayahKabupatenController;
use App\Models\Mahasiswa;

Route::post('auth-cek', [AuthController::class, 'index']);
Route::get('cetak-kartu-pendaftaran/{url_id}', [PendaftarController::class, 'dataPendaftar']);

Route::get('data-program-studi', [ProgramStudiController::class, 'index']);
Route::get('data-fakultas', [FakultasController::class, 'index']);
Route::get('data-referensi', [ReferensiPilihanController::class, 'index']);
Route::get('data-kabupaten', [WilayahKabupatenController::class, 'index']);
Route::get('data-desa', [WilayahDesaController::class, 'index']);
Route::get('cek-email', [PenggunaController::class, 'cekEmail']);
Route::get('cek-nim', [MahasiswaController::class, 'cekNim']);
Route::post('simpan-pendaftaran-mahasiswa', [MahasiswaController::class, 'simpanPendaftaranMahasiswa']);


Route::middleware('jwt.auth.refresh')->group(function () {
    //endpoint umum
    Route::get('cek-akses', [UserRoleController::class, 'cekAkses']);
    Route::get('user-role-detail/{user_id}', [UserRoleController::class, 'getUserRole']);
    Route::get('role-user', [AuthController::class, 'roleUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('upload-gambar-beasiswa', [BeasiswaController::class, 'uploadGambarBeasiswa']);

    Route::get('data-role', [RoleController::class, 'index']);
    Route::get('data-jenis-beasiswa', [JenisBeasiswaController::class, 'index']);
    Route::get('data-beasiswa', [BeasiswaController::class, 'index']);
    // Route::get('data-pekerjaan', [PekerjaanController::class, 'index']);
    // Route::get('data-pendapatan', [PendapatanController::class, 'index']);
    // Route::get('data-sumber-biaya', [SumberBiayaController::class, 'index']);
    // Route::get('data-pendidikan', [PendidikanController::class, 'index']);
    // Route::get('data-sumber-air', [SumberAirController::class, 'index']);
    // Route::get('data-sumber-listrik', [SumberListrikController::class, 'index']);
    // Route::get('data-mck', [MckController::class, 'index']);


    Route::resource('identitas', IdentitasController::class);

    Route::middleware(['cek.akses:admin'])->group(function () {
        Route::resource('pekerjaan', PekerjaanController::class);
        Route::resource('pendapatan', PendapatanController::class);
        Route::resource('pendidikan', PendidikanController::class);
        Route::resource('sumber-biaya', SumberBiayaController::class);
        Route::resource('sumber-air', SumberAirController::class);
        Route::resource('sumber-listrik', SumberListrikController::class);
        Route::resource('mck', MckController::class);
        Route::resource('referensi-pilihan', ReferensiPilihanController::class);
        Route::resource('fakultas', FakultasController::class);
        Route::resource('program-studi', ProgramStudiController::class);
        Route::resource('role', RoleController::class);
        Route::resource('user-role', UserRoleController::class);
        Route::resource('pengguna', PenggunaController::class);
        Route::resource('jenis-beasiswa', JenisBeasiswaController::class);
        Route::resource('beasiswa', BeasiswaController::class);
        Route::resource('syarat', SyaratController::class);
        Route::resource('soal-wawancara', SoalWawancaraController::class);
        Route::put('ganti-nomor-soal-wawancara/{id}', [SoalWawancaraController::class, 'gantiNomorSoalWawancara']);
    });

    Route::middleware(['cek.akses:mahasiswa'])->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::resource('pendidikan-akhir', PendidikanAkhirController::class);
        Route::resource('orang-tua', OrangTuaController::class);
        Route::resource('nilai-raport', NilaiRaportController::class);
        Route::resource('pendaftar', PendaftarController::class);
        Route::resource('upload-syarat', UploadSyaratController::class);
        Route::resource('rumah', RumahController::class);

        Route::put('batalkan-pendaftaran/{id}', [PendaftarController::class, 'pembatalan']);
        Route::put('pendaftaran-selesai/{id}', [PendaftarController::class, 'pendaftaranSelesai']);
        Route::put('daftar-kembali/{id}', [PendaftarController::class, 'daftarKembali']);
    });
});



Route::fallback(function () {
    return response()->json([
        'status' => false,
        'message' => 'Route not found',
        'data' => null,
    ], 404);
});
