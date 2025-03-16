<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AuthController;

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/login', [WebController::class, 'login'])->name('login');
Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
Route::get('/pekerjaan', [WebController::class, 'pekerjaan'])->name('pekerjaan');
Route::get('/pendapatan', [WebController::class, 'pendapatan'])->name('pendapatan');
Route::get('/pendidikan', [WebController::class, 'pendidikan'])->name('pendidikan');
Route::get('/sumber-biaya', [WebController::class, 'sumberBiaya'])->name('sumber-biaya');
Route::get('/sumber-air', [WebController::class, 'sumberAir'])->name('sumber-air');
Route::get('/sumber-listrik', [WebController::class, 'sumberListrik'])->name('sumber-listrik');
Route::get('/mck', [WebController::class, 'mck'])->name('mck');
Route::get('/fakultas', [WebController::class, 'fakultas'])->name('fakultas');
Route::get('/program-studi', [WebController::class, 'programStudi'])->name('program-studi');
Route::get('/role', [WebController::class, 'role'])->name('role');
Route::get('/pengguna', [WebController::class, 'pengguna'])->name('pengguna');
Route::get('/jenis-beasiswa', [WebController::class, 'jenisBeasiswa'])->name('jenis-beasiswa');
Route::get('/referensi-pilihan', [WebController::class, 'referensiPilihan'])->name('referensi-pilihan');
Route::get('/beasiswa', [WebController::class, 'beasiswa'])->name('beasiswa');
Route::get('/syarat/{beasiswa_id?}', [WebController::class, 'syarat'])->name('syarat');
Route::get('/soal-wawancara/{beasiswa_id?}', [WebController::class, 'soalWawancara'])->name('soal-wawancara');

Route::get('/mahasiswa', [WebController::class, 'dataMahasiswa'])->name('mahasiswa');
Route::get('/pendidikan-akhir', [WebController::class, 'pendidikanAkhir'])->name('pendidikan-akhir');
Route::get('/orang-tua', [WebController::class, 'dataOrangTua'])->name('orang-tua');
Route::get('/identitas', [WebController::class, 'identitas'])->name('identitas');
Route::get('/pendaftar', [WebController::class, 'pendaftar'])->name('pendaftar');
Route::get('/nilai-raport', [WebController::class, 'nilaiRaport'])->name('nilai-raport');
Route::get('/rumah', [WebController::class, 'rumah'])->name('rumah');
Route::get('/berkas-pendaftaran/{id}', [WebController::class, 'berkasPendaftaran'])->name('berkas-pendaftaran');

Route::get('/cetak-kartu-pendaftaran/{url_id}', [WebController::class, 'cetakKartuPendaftaran'])->name('cetak-kartu-pendaftaran');
Route::get('/daftar-baru/{kategori}', [WebController::class, 'daftarBaru'])->name('daftar-baru');
Route::get('/verifikator/{id}', [WebController::class, 'verifikator'])->name('verifikator');
Route::get('/verifikasi-berkas', [WebController::class, 'verifikasiBerkas'])->name('verifikasi-berkas');

Route::get('/pewawancara/{id}', [WebController::class, 'pewawancara'])->name('pewawancara');

Route::get('/wawancara', [WebController::class, 'wawancara'])->name('wawancara');
Route::get('/peserta-wawancara/{id}', [WebController::class, 'pesertaWawancara'])->name('peserta-wawancara');
Route::get('/registrasi-peserta', [WebController::class, 'registrasiPeserta'])->name('registrasi-peserta');
