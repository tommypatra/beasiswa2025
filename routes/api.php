<?php

use App\Models\Syarat;
use App\Models\Beasiswa;
use App\Models\OrangTua;
use App\Models\Mahasiswa;
use App\Models\Pendaftar;
use App\Models\SumberAir;
use App\Models\SkPenerima;
use App\Models\JadwalUjian;
use App\Models\NilaiRaport;
use App\Models\Pewawancara;
use App\Models\Verifikator;
use Illuminate\Http\Request;
use App\Models\JenisBeasiswa;
use App\Models\SoalWawancara;
use App\Models\SurveiPeserta;
use App\Models\WawancaraNilai;
use App\Models\PesertaWawancara;
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
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RuanganController;
use App\Http\Middleware\CekAksesMiddleware;
use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\PenerimaController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PredikatController;
use App\Http\Controllers\SurveyorController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\IdentitasController;
use App\Http\Controllers\KelulusanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\SesiUjianController;
use App\Http\Controllers\SumberAirController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\SkPenerimaController;
use App\Http\Controllers\JadwalUjianController;
use App\Http\Controllers\NilaiRaportController;
use App\Http\Controllers\PewawancaraController;
use App\Http\Controllers\SubKegiatanController;
use App\Http\Controllers\SumberBiayaController;
use App\Http\Controllers\SurveiNilaiController;
use App\Http\Controllers\VerifikatorController;
use App\Http\Controllers\WilayahDesaController;
use App\Http\Middleware\JwtAuthenticateRefresh;
use App\Http\Controllers\BukuRekeningController;
use App\Http\Controllers\PesertaUjianController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\RuanganUjianController;
use App\Http\Controllers\UploadSyaratController;
use App\Http\Controllers\JenisBeasiswaController;
use App\Http\Controllers\PesertaSurveiController;
use App\Http\Controllers\SoalWawancaraController;
use App\Http\Controllers\SumberListrikController;
use App\Http\Controllers\SurveiPesertaController;
use App\Http\Controllers\StatusOrangTuaController;
use App\Http\Controllers\WawancaraNilaiController;
use App\Http\Controllers\PendidikanAkhirController;
use App\Http\Controllers\PengaturanUjianController;
use App\Http\Controllers\PesertaWawancaraController;
use App\Http\Controllers\ReferensiPilihanController;
use App\Http\Controllers\VerifikasiBerkasController;
use App\Http\Controllers\WilayahKabupatenController;
use App\Http\Controllers\DokumentasiSurveiController;
use App\Http\Controllers\VerifikatorLaporanController;
use App\Http\Controllers\VerifikatorPenerimaController;
use App\Http\Controllers\VerifikatorPendaftarController;

Route::post('auth-cek', [AuthController::class, 'index']);
Route::post('cek-data-akun-sia', [AuthController::class, 'cekDataAkunSia']);
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
    Route::get('cari-mahasiswa', [MahasiswaController::class, 'index']);

    Route::get('daftar-pendaftar-beasiswa/{id}', [PendaftarController::class, 'daftarPendaftar']);
    Route::get('get-data-pendaftar/{id}', [PendaftarController::class, 'getData']);
    Route::get('get-data-beasiswa/{id}', [BeasiswaController::class, 'show']);
    Route::get('get-data-mahasiswa/{id}', [MahasiswaController::class, 'show']);
    Route::get('get-data-orang-tua/{id}', [OrangTuaController::class, 'dataOrangTua']);
    Route::get('get-data-pendidikan-akhir/{id}', [PendidikanAkhirController::class, 'dataPendidikanAkhir']);
    Route::get('get-data-upload/{id}', [UploadSyaratController::class, 'dataUpload']);
    Route::get('get-data-raport/{id}', [NilaiRaportController::class, 'dataRaport']);
    Route::get('get-data-kondisi-rumah/{id}', [RumahController::class, 'dataKondisiRumah']);
    Route::get('get-data-dokumentasi-survei/{id}', [DokumentasiSurveiController::class, 'dataDokumentasiSurvei']);
    Route::get('get-data-sk-penerima/{id}', [SkPenerimaController::class, 'show']);
    Route::get('get-data-sk-penerima-mahasiswa', [PenerimaController::class, 'skPenerimaMahasiswa']);
    Route::get('get-data-sk-beasiswa/{id}', [SkPenerimaController::class, 'skPenerimaMahasiswa']);
    Route::get('get-data-peserta-wawancara', [PesertaWawancaraController::class, 'index']);
    Route::get('get-data-dokumen-upload/{id}/', [UploadSyaratController::class, 'dataDokumenUpload']);

    Route::get('get-rekap-kabupaten/{beasiswa_id}/', [PendaftarController::class, 'rekapKabupaten']);

    // Route::get('data-pekerjaan', [PekerjaanController::class, 'index']);
    // Route::get('data-pendapatan', [PendapatanController::class, 'index']);
    // Route::get('data-sumber-biaya', [SumberBiayaController::class, 'index']);
    // Route::get('data-pendidikan', [PendidikanController::class, 'index']);
    // Route::get('data-sumber-air', [SumberAirController::class, 'index']);
    // Route::get('data-sumber-listrik', [SumberListrikController::class, 'index']);
    // Route::get('data-mck', [MckController::class, 'index']);

    Route::resource('identitas', IdentitasController::class);

    Route::middleware(['cek.akses:verifikator'])->group(function () {
        Route::resource('verifikasi-berkas', VerifikasiBerkasController::class);
        Route::get('peserta-verifikasi', [VerifikasiBerkasController::class, 'pesertaVerifikasi']);
        Route::get('data-upload-syarat', [UploadSyaratController::class, 'dataUploadSyarat']);
        Route::put('simpan-validasi-syarat/{id}', [VerifikatorPendaftarController::class, 'simpanValidasiSyarat']);
        Route::put('simpan-validasi-final/{id}', [VerifikatorPendaftarController::class, 'simpanValidasiFinal']);
        Route::post('reupload-dokumen-syarat', [VerifikasiBerkasController::class, 'reuploadDokumenSyarat']);
    });

    Route::middleware(['cek.akses:surveyor'])->group(function () {
        Route::resource('peserta-survei', PesertaSurveiController::class);
        Route::resource('survei-nilai', SurveiNilaiController::class);
        Route::get('proses-survei/{id}', [SurveiNilaiController::class, 'prosesSurvei']);
        // Route::put('akhiri-survei/{id}', [SurveiNilaiController::class, 'akhiriSurvei']);
        Route::get('survei', [PesertaSurveiController::class, 'survei']);
        Route::get('pilih-peserta-survei', [PesertaSurveiController::class, 'pilihPesertaSurvei']);

        Route::resource('dokumentasi-survei', DokumentasiSurveiController::class);

        Route::put('pendidikan-akhir/update-survei/{id}', [SurveiNilaiController::class, 'updateSurveiPendidikanAkhir']);
        Route::put('raport/update-survei/{id}', [SurveiNilaiController::class, 'updateSurveiRaport']);
        Route::put('kondisi-rumah/update-survei/{id}', [SurveiNilaiController::class, 'updateSurveiKondisiRumah']);
        Route::put('orang-tua/update-survei/{id}', [SurveiNilaiController::class, 'updateSurveiOrangTUa']);
        Route::put('dokumen-upload/update-survei/{id}', [SurveiNilaiController::class, 'updateSurveiDokumenUpload']);
    });

    Route::get('wawancara', [PesertaWawancaraController::class, 'wawancara']);
    Route::get('pewawancara', [PesertaWawancaraController::class, 'pewawancara']);
    Route::get('peserta-verifikasi/{beasiswa_id}/{hasil}', [VerifikatorController::class, 'getPesertaVerifikasi']);

    Route::middleware(['cek.akses:admin,pewawancara'])->group(function () {
        Route::get('cetak-wawancara/{beasiswa_id}', [PewawancaraController::class, 'cetakWawancara']);
    });

    Route::middleware(['cek.akses:pewawancara'])->group(function () {
        // Route::resource('peserta-wawancara', PesertaWawancaraController::class);
        Route::get('daftar-peserta-wawancara', [PesertaWawancaraController::class, 'index']);
        Route::get('cari-peserta-wawancara/{id}', [PesertaWawancaraController::class, 'show']);

        Route::get('cari-wawancara-id/{id}', [PesertaWawancaraController::class, 'cariWawancaraId']);


        Route::resource('wawancara-nilai', WawancaraNilaiController::class);
        Route::get('proses-wawancara/{id}', [WawancaraNilaiController::class, 'prosesWawancara']);
        Route::put('akhiri-wawancara/{id}', [WawancaraNilaiController::class, 'akhiriWawancara']);
        Route::get('pilih-peserta-wawancara', [PesertaWawancaraController::class, 'pilihPesertaWawancara']);


        Route::resource('peserta-wawancara', PesertaWawancaraController::class);
    });

    Route::middleware(['cek.akses:pengelola'])->group(function () {
        Route::resource('sk-penerima', SkPenerimaController::class);
        Route::resource('penerima', PenerimaController::class);
        Route::resource('verifikator-penerima', VerifikatorPenerimaController::class);
        Route::resource('verifikator-laporan', VerifikatorLaporanController::class);


        Route::get('verifikasi-laporan/penerima/{sk_penerima_id}', [VerifikatorLaporanController::class, 'daftarPenerimaVerifikasi']);
        Route::get('verifikasi-laporan/daftar', [VerifikatorLaporanController::class, 'daftarSkVerifikasi']);
        Route::put('verifikasi-laporan/simpan/{penerima_id}', [VerifikatorLaporanController::class, 'simpanHasilVerifikasi']);

        Route::put('pengelola/registasi-peserta-wawancara/{id}', [PesertaWawancaraController::class, 'registasiPeserta']);
        Route::get('pengelola/peserta-verifikasi', [VerifikatorPendaftarController::class, 'pesertaVerifikasi']);
        Route::get('data-peserta-lulus/{beasiswa_id}/{sk_penerima_id}', [KelulusanController::class, 'dataPesertaLulus']);
        Route::get('sinkron-rekening/{sk_penerima_id}', [BukuRekeningController::class, 'sinkronRekening']);
    });

    Route::middleware(['cek.akses:admin'])->group(function () {
        Route::resource('ruangan', RuanganController::class);
        Route::resource('pengaturan-ujian', PengaturanUjianController::class);
        Route::resource('ruangan-ujian', RuanganUjianController::class);
        Route::resource('sesi-ujian', SesiUjianController::class);
        Route::resource('pekerjaan', PekerjaanController::class);
        Route::resource('kelulusan', KelulusanController::class);
        Route::post('proses-kelulusan', [KelulusanController::class, 'prosesKelulusan']);
        Route::delete('hapus-kelulusan/{beasiswa_id}', [KelulusanController::class, 'hapusKelulusan']);

        Route::delete('syarat-hapus-contoh/{beasiswa_id}', [SyaratController::class, 'hapusContoh']);

        Route::get('generate-nilai-akhir-wawancara/{id}', [WawancaraNilaiController::class, 'generateNilaiAkhir']);


        Route::get('batalkan-finalisasi/{id}', [PendaftarController::class, 'batalkanFinalisasi']);

        Route::resource('monitoring', MonitoringController::class);
        Route::resource('pendapatan', PendapatanController::class);
        Route::resource('pendidikan', PendidikanController::class);
        Route::resource('sumber-biaya', SumberBiayaController::class);
        Route::resource('sumber-air', SumberAirController::class);
        Route::resource('sumber-listrik', SumberListrikController::class);
        Route::resource('mck', MckController::class);
        Route::resource('referensi-pilihan', ReferensiPilihanController::class);
        Route::resource('predikat', PredikatController::class);
        Route::resource('kegiatan', KegiatanController::class);
        Route::resource('butir-kegiatan', SubKegiatanController::class);
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

        //untuk verifikator
        Route::get('verifikator/{beasiswa_id}', [VerifikatorController::class, 'index']);
        Route::get('verifikator/show/{id}', [VerifikatorController::class, 'show']);
        Route::post('verifikator', [VerifikatorController::class, 'store']);
        Route::delete('verifikator/{id}', [VerifikatorController::class, 'destroy']);
        Route::put('verifikator/{id}', [VerifikatorController::class, 'update']);
        Route::resource('verifikator-pendaftar', VerifikatorPendaftarController::class);

        //untuk surveyor
        Route::get('surveyor/{beasiswa_id}', [SurveyorController::class, 'index']);
        Route::get('surveyor/show/{id}', [SurveyorController::class, 'show']);
        Route::post('surveyor', [SurveyorController::class, 'store']);
        Route::delete('surveyor/{id}', [SurveyorController::class, 'destroy']);
        Route::put('surveyor/{id}', [SurveyorController::class, 'update']);
        Route::resource('surveyor-peserta', SurveiPesertaController::class);

        //untuk pewawancara
        Route::get('daftar-pewawancara/{beasiswa_id}', [PewawancaraController::class, 'dataPewawancara']);
        Route::get('pewawancara/{beasiswa_id}', [PewawancaraController::class, 'index']);
        Route::get('pewawancara/show/{id}', [PewawancaraController::class, 'show']);
        Route::post('pewawancara', [PewawancaraController::class, 'store']);
        Route::delete('pewawancara/{id}', [PewawancaraController::class, 'destroy']);
        Route::put('pewawancara/{id}', [PewawancaraController::class, 'update']);
        Route::get('peserta-ujian-wawancara', [PesertaWawancaraController::class, 'daftarPesertaWawancara']);


        Route::get('tukar-peserta-wawancara/{id_asal}/{id_tujuan}', [PesertaWawancaraController::class, 'tukarPesertaWawancara']);

        Route::resource('admin-peserta-wawancara', PesertaWawancaraController::class);
        Route::delete('hapus-contoh-format-laporan/{id}', [SubKegiatanController::class, 'hapusContohFormatLaporan']);

        //generate jadwal ujian CAT
        Route::resource('jadwal-ujian', JadwalUjianController::class);
        Route::get('hapus-jadwal-ujian/{beasiswa_id}', [JadwalUjianController::class, 'hapusJadwalUjian']);
        Route::get('hapus-peserta-ujian/{beasiswa_id}', [PesertaUjianController::class, 'hapusPesertaUjian']);

        Route::get('generate-jadwal-ujian/{id}', [JadwalUjianController::class, 'generateJadwal']);
    });

    Route::middleware(['cek.akses:mahasiswa'])->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::resource('pendidikan-akhir', PendidikanAkhirController::class);
        Route::resource('orang-tua', OrangTuaController::class);
        Route::resource('nilai-raport', NilaiRaportController::class);
        Route::resource('buku-rekening', BukuRekeningController::class);
        Route::resource('pendaftar', PendaftarController::class);
        Route::resource('upload-syarat', UploadSyaratController::class);
        Route::resource('rumah', RumahController::class);
        Route::resource('laporan', LaporanController::class);

        Route::get('laporan-mahasiswa/{kegiatan_id}', [LaporanController::class, 'laporanMahasiswa']);


        Route::get('aktifkan-nomor-rekening/{rekening_id}', [BukuRekeningController::class, 'aktifkanRekening']);
        Route::get('detail-pendaftar/{pendaftar_id}', [PendaftarController::class, 'detailPendaftar']);

        Route::put('batalkan-pendaftaran/{id}', [PendaftarController::class, 'pembatalan']);
        Route::put('pendaftaran-selesai/{id}', [PendaftarController::class, 'pendaftaranSelesai']);
        Route::put('daftar-kembali/{id}', [PendaftarController::class, 'daftarKembali']);
        Route::get('finalisasi-laporan/{id}', [LaporanController::class, 'finalisasiLaporan']);

        Route::put('ganti-nomor-rekening/{id}', [PenerimaController::class, 'gantiNomorRekening']);
    });
});



Route::fallback(function () {
    return response()->json([
        'status' => false,
        'message' => 'Route not found',
        'data' => null,
    ], 404);
});
