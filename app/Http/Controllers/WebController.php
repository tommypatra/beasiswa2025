<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{

    public function googleRespon() {}

    public function session()
    {
        dd(auth()->user());
    }

    public function ruangan()
    {
        return view('app.ruangan');
    }

    public function loginAdmin()
    {
        return view('login');
    }

    public function loginSia()
    {
        return view('login_sia');
    }

    public function cetakDataPendaftar($id)
    {
        return view('cetak.cetak_data_pendaftar', ['beasiswa_id' => $id]);
    }

    public function cetakDataKelulusan($id)
    {
        return view('cetak.cetak_data_kelulusan', ['beasiswa_id' => $id]);
    }


    public function cetakHasilWawancara($id)
    {
        return view('cetak.cetak_hasil_wawancara', ['beasiswa_id' => $id]);
    }

    public function cetakRekapWawancara($id)
    {
        return view('cetak.cetak_rekap_wawancara', ['beasiswa_id' => $id]);
    }

    public function cetakPenerimaBeasiswa($sk_penerima_id)
    {
        return view('cetak.cetak_penerima_beasiswa', ['sk_penerima_id' => $sk_penerima_id]);
    }


    public function importPenerimaBeasiswa($id)
    {
        return view('app.import_penerima_beasiswa', ['sk_penerima_id' => $id]);
    }

    public function kelulusan($id)
    {
        return view('app.kelulusan', ['beasiswa_id' => $id]);
    }

    public function cat($id)
    {
        return view('app.cat', ['beasiswa_id' => $id]);
    }

    public function skPenerima()
    {
        return view('app.sk_penerima');
    }

    public function skPenerimaMahasiswa()
    {
        return view('app.sk_penerima_mahasiswa');
    }


    public function dashboard()
    {
        return view('app.dashboard');
    }

    public function pekerjaan()
    {
        return view('app.pekerjaan');
    }

    public function pendapatan()
    {
        return view('app.pendapatan');
    }

    public function predikat()
    {
        return view('app.predikat');
    }

    public function butirKegiatan()
    {
        return view('app.butir_kegiatan');
    }

    public function monitoring()
    {
        return view('app.monitoring');
    }

    public function pendidikan()
    {
        return view('app.pendidikan');
    }

    public function bukuRekening()
    {
        return view('app.buku_rekening');
    }

    public function daftarBaru($kategori)
    {
        if ($kategori == 'pegawai') {
            $view = 'daftar_pegawai';
        } else {
            $view = 'daftar_mahasiswa';
        }
        return view($view);
    }
    public function sumberBiaya()
    {
        return view('app.sumber_biaya');
    }

    public function sumberAir()
    {
        return view('app.sumber_air');
    }

    public function sumberListrik()
    {
        return view('app.sumber_listrik');
    }

    public function mck()
    {
        return view('app.mck');
    }

    public function fakultas()
    {
        return view('app.fakultas');
    }

    public function programStudi()
    {
        return view('app.program_studi');
    }

    public function role()
    {
        return view('app.role');
    }

    public function pengguna()
    {
        return view('app.pengguna');
    }

    public function jenisBeasiswa()
    {
        return view('app.jenis_beasiswa');
    }

    public function beasiswa()
    {
        return view('app.beasiswa');
    }

    public function syarat($id = null)
    {
        return view('app.syarat', ['beasiswa_id' => $id]);
    }

    public function soalWawancara($id = null)
    {
        return view('app.soal_wawancara', ['beasiswa_id' => $id]);
    }

    public function dataMahasiswa()
    {
        return view('app.mahasiswa');
    }

    public function pendidikanAkhir()
    {
        return view('app.pendidikan_akhir');
    }

    public function dataOrangTua()
    {
        return view('app.orang_tua');
    }

    public function identitas()
    {
        return view('app.identitas');
    }

    public function rumah()
    {
        return view('app.rumah');
    }

    public function pendaftar()
    {
        return view('app.daftar_beasiswa');
    }

    public function nilaiRaport()
    {
        return view('app.nilai_raport');
    }

    public function referensiPilihan()
    {
        return view('app.referensi_pilihan');
    }

    public function berkasPendaftaran($id)
    {
        return view('app.berkas_pendaftaran', ['id' => $id]);
    }

    public function cetakKartuPendaftaran($url_id)
    {
        return view('cetak.cetak_kartu_pendaftaran', ['url_id' => $url_id]);
    }

    public function verifikator($id)
    {
        return view('app.verifikator', ['beasiswa_id' => $id]);
    }

    public function surveyor($id)
    {
        return view('app.surveyor', ['beasiswa_id' => $id]);
    }

    public function pendaftarBeasiswa($id)
    {
        return view('app.pendaftar_beasiswa', ['beasiswa_id' => $id]);
    }

    public function pewawancara($id)
    {
        return view('app.pewawancara', ['beasiswa_id' => $id]);
    }

    public function verifikasiBerkas()
    {
        return view('app.verifikasi_berkas');
    }

    public function wawancara()
    {
        return view('app.wawancara');
    }

    public function pesertaWawancara($id)
    {
        return view('app.peserta_wawancara', ['id' => $id]);
    }

    public function survei()
    {
        return view('app.survei');
    }

    public function pesertaSurvei($id)
    {
        return view('app.peserta_survei', ['id' => $id]);
    }

    public function dashboardBeasiswa($id)
    {
        return view('app.dashboard_beasiswa', ['beasiswa_id' => $id]);
    }

    public function registrasiPeserta()
    {
        return view('app.registrasi_peserta');
    }

    public function verifikasiPeserta()
    {
        return view('app.verifikasi_peserta');
    }


    //untuk pengaturan cat
    public function pengaturanCat($id)
    {
        return view('app.cat.pengaturan', ['beasiswa_id' => $id]);
    }

    public function ruanganCat($id)
    {
        return view('app.cat.ruangan', ['beasiswa_id' => $id]);
    }

    public function sesiCat($id)
    {
        return view('app.cat.sesi', ['beasiswa_id' => $id]);
    }

    public function jadwalCat($id)
    {
        return view('app.cat.jadwal', ['beasiswa_id' => $id]);
    }
}
