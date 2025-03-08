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

    public function login()
    {
        return view('login');
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

    public function pendidikan()
    {
        return view('app.pendidikan');
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
        return view('app.pendaftar');
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
}
