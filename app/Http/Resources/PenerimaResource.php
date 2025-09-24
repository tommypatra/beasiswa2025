<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenerimaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        $rekening_aktif = $this->user->bukuRekening->first(); // atau [0]
        if ($rekening_aktif) {
            $tersedia_buku_rekening_id   = $rekening_aktif->id;
            $tersedia_nomor   = $rekening_aktif->nomor;
            $tersedia_bank    = $rekening_aktif->bank;
            $tersedia_nama_pemilik = $rekening_aktif->nama_pemilik;
            $tersedia_foto_buku = $rekening_aktif->foto_buku;
        } else {
            $tersedia_buku_rekening_id   = null;
            $tersedia_nomor   = null;
            $tersedia_bank    = null;
            $tersedia_nama_pemilik = null;
            $tersedia_foto_buku = null;
        }

        $rekening_terupload = $this->bukuRekening;
        if ($rekening_terupload) {
            $terupload_buku_rekening_id   = $rekening_terupload->id;
            $terupload_nomor   = $rekening_terupload->nomor;
            $terupload_bank    = $rekening_terupload->bank;
            $terupload_nama_pemilik = $rekening_terupload->nama_pemilik;
            $terupload_foto_buku = $rekening_terupload->foto_buku;
        } else {
            $terupload_buku_rekening_id   = null;
            $terupload_nomor   = null;
            $terupload_bank    = null;
            $terupload_nama_pemilik = null;
            $terupload_foto_buku = null;
        }

        return [
            'sk_penerima_id' => $this->sk_penerima_id,
            'penerima_id' => $this->id,
            'is_mobile_dev' => isMobileDev(),
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'email' => $this->user->email,

            'terupload_buku_rekening_id'  => $terupload_buku_rekening_id,
            'terupload_nomor'  => $terupload_nomor,
            'terupload_bank'    => $terupload_bank,
            'terupload_nama_pemilik' => $terupload_nama_pemilik,
            'terupload_foto_buku' => $terupload_foto_buku,

            'tersedia_buku_rekening_id' => $tersedia_buku_rekening_id,
            'tersedia_nomor' => $tersedia_nomor,
            'tersedia_bank' => $tersedia_bank,
            'tersedia_nama_pemilik' => $tersedia_nama_pemilik,
            'tersedia_foto_buku' => $tersedia_foto_buku,

            'identitas_id' => $this->user->identitas->id,
            'foto' => $this->user->identitas->foto,
            'jenis_kelamin' => $this->user->identitas->jenis_kelamin,
            'no_hp' => $this->user->identitas->no_hp,
            'tempat_lahir' => $this->user->identitas->tempat_lahir,
            'tanggal_lahir' => $this->user->identitas->tanggal_lahir,
            'alamat' => $this->user->identitas->alamat,
            'desa' => $this->user->identitas->desa,
            'kecamatan' => $this->user->identitas->kecamatan,
            'kabupaten' => $this->user->identitas->kabupaten,
            'provinsi' => $this->user->identitas->provinsi,

            'mahasiswa_id' => $this->user->mahasiswa->id,
            'nim' => $this->user->mahasiswa->nim,
            'kartu_mahasiswa' => $this->user->mahasiswa->kartu_mahasiswa,
            'tahun_masuk' => $this->user->mahasiswa->tahun_masuk,
            'program_studi_id' => $this->user->mahasiswa->programStudi->id,
            'program_studi' => $this->user->mahasiswa->programStudi->nama,
            'fakultas_id' => $this->user->mahasiswa->programStudi->fakultas->id,
            'fakultas' => $this->user->mahasiswa->programStudi->fakultas->nama,
        ];
    }
}
