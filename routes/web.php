<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AuthController;

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/', [WebController::class, 'loginSia']);
Route::get('/login-email', [WebController::class, 'loginAdmin'])->name('login-email');
Route::get('/login', [WebController::class, 'loginSia'])->name('login');
Route::get('/ruangan', [WebController::class, 'ruangan'])->name('ruangan');
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

Route::get('/monitoring', [WebController::class, 'monitoring'])->name('monitoring');
Route::get('/predikat', [WebController::class, 'predikat'])->name('predikat');
Route::get('/butir-kegiatan', [WebController::class, 'butirKegiatan'])->name('butir-kegiatan');



Route::get('/jenis-beasiswa', [WebController::class, 'jenisBeasiswa'])->name('jenis-beasiswa');
Route::get('/referensi-pilihan', [WebController::class, 'referensiPilihan'])->name('referensi-pilihan');
Route::get('/beasiswa', [WebController::class, 'beasiswa'])->name('beasiswa');
Route::get('/syarat/{beasiswa_id}', [WebController::class, 'syarat'])->name('syarat');
Route::get('/cat/{beasiswa_id}', [WebController::class, 'cat'])->name('cat');
Route::get('/pendaftar-beasiswa/{beasiswa_id}', [WebController::class, 'pendaftarBeasiswa'])->name('pendaftar-beasiswa');
Route::get('/soal-wawancara/{beasiswa_id?}', [WebController::class, 'soalWawancara'])->name('soal-wawancara');

Route::get('/mahasiswa', [WebController::class, 'dataMahasiswa'])->name('mahasiswa');
Route::get('/pendidikan-akhir', [WebController::class, 'pendidikanAkhir'])->name('pendidikan-akhir');
Route::get('/orang-tua', [WebController::class, 'dataOrangTua'])->name('orang-tua');
Route::get('/identitas', [WebController::class, 'identitas'])->name('identitas');
Route::get('/pendaftar', [WebController::class, 'pendaftar'])->name('pendaftar');
Route::get('/buku-rekening', [WebController::class, 'bukuRekening'])->name('buku-rekening');
Route::get('/nilai-raport', [WebController::class, 'nilaiRaport'])->name('nilai-raport');
Route::get('/rumah', [WebController::class, 'rumah'])->name('rumah');
Route::get('/berkas-pendaftaran/{id}', [WebController::class, 'berkasPendaftaran'])->name('berkas-pendaftaran');

Route::get('/daftar-baru/{kategori}', [WebController::class, 'daftarBaru'])->name('daftar-baru');
Route::get('/verifikator/{beasiswa_id}', [WebController::class, 'verifikator'])->name('verifikator');
Route::get('/verifikasi-berkas', [WebController::class, 'verifikasiBerkas'])->name('verifikasi-berkas');


Route::get('/kelulusan/{beasiswa_id}', [WebController::class, 'kelulusan'])->name('kelulusan');
Route::get('/surveyor/{beasiswa_id}', [WebController::class, 'surveyor'])->name('surveyor');
Route::get('/sk-penerima', [WebController::class, 'skPenerima'])->name('sk-penerima');
Route::get('/sk-penerima-mahasiswa', [WebController::class, 'skPenerimaMahasiswa'])->name('sk-penerima-mahasiswa');

Route::get('/pewawancara/{beasiswa_id}', [WebController::class, 'pewawancara'])->name('pewawancara');

Route::get('/survei', [WebController::class, 'survei'])->name('survei');
Route::get('/wawancara', [WebController::class, 'wawancara'])->name('wawancara');
Route::get('/peserta-wawancara/{id}', [WebController::class, 'pesertaWawancara'])->name('peserta-wawancara');
Route::get('/peserta-survei/{id}', [WebController::class, 'pesertaSurvei'])->name('peserta-survei');

Route::get('/dashboard-beasiswa/{beasiswa_id}', [WebController::class, 'dashboardBeasiswa'])->name('dashboard-beasiswa');
Route::get('/registrasi-peserta', [WebController::class, 'registrasiPeserta'])->name('registrasi-peserta');
Route::get('/verifikasi-peserta', [WebController::class, 'verifikasiPeserta'])->name('verifikasi-peserta');

Route::get('/verifikasi-laporan', [WebController::class, 'verifikasiLaporan'])->name('verifikasi-laporan');
Route::get('/import-penerima-beasiswa/{sk_penerima_id}', [WebController::class, 'importPenerimaBeasiswa'])->name('import-penerima-beasiswa');
Route::get('/laporan-penerima-beasiswa/{sk_penerima_id}', [WebController::class, 'laporanPenerimaBeasiswa'])->name('laporan-penerima-beasiswa');

//untuk cetak
Route::get('/cetak-kartu-pendaftaran/{url_id}', [WebController::class, 'cetakKartuPendaftaran'])->name('cetak-kartu-pendaftaran');
Route::get('/cetak-data-pendaftar/{beasiswa_id}', [WebController::class, 'cetakDataPendaftar'])->name('cetak-data-pendaftar');
Route::get('/cetak-data-kelulusan/{beasiswa_id}', [WebController::class, 'cetakDataKelulusan'])->name('cetak-data-kelulusan');
Route::get('/cetak-hasil-wawancara/{beasiswa_id}/{pewawancara_id?}', [WebController::class, 'cetakHasilWawancara'])->name('cetak-hasil-wawancara');
Route::get('/cetak-rekap-wawancara/{beasiswa_id}', [WebController::class, 'cetakRekapWawancara'])->name('cetak-rekap-wawancara');
Route::get('/cetak-penerima-mahasiswa/{sk_penerima_id}', [WebController::class, 'cetakPenerimaMahasiswa'])->name('cetak-penerima-mahasiswa');
Route::get('/cetak-progress-survei/{beasiswa_id}', [WebController::class, 'cetakProgressSurvei'])->name('cetak-progress-survei');

//route untuk pengaturan cat
Route::get('/pengaturan-cat/{id}', [WebController::class, 'pengaturanCat']);
Route::get('/ruangan-cat/{id}', [WebController::class, 'ruanganCat']);
Route::get('/sesi-cat/{id}', [WebController::class, 'sesiCat']);
Route::get('/jadwal-cat/{id}', [WebController::class, 'jadwalCat']);
