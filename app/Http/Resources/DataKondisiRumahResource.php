<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataKondisiRumahResource extends JsonResource
{
    protected $jumlahPilihan;

    // Konstruktor untuk membawa data tambahan jumlahPilihan
    public function __construct($resource, $jumlahPilihan = null)
    {
        parent::__construct($resource);
        $this->jumlahPilihan = $jumlahPilihan;
    }


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'user_id' => $this->id,
            'nama' => $this->name,
            'email' => $this->email,
            'jumlah_pilihan' => $this->jumlahPilihan,
            'jumlah_orang_tinggal' => $this->rumah->jumlah_orang_tinggal,
            'rumah_id' => $this->rumah->id,
            'luas_bangunan' => $this->rumah->luas_bangunan,
            'luas_tanah' => $this->rumah->luas_tanah,

            'sumber_listrik_id' => $this->rumah->pilihanSumberListrik->id,
            'sumber_listrik_nama' => $this->rumah->pilihanSumberListrik->nama,
            'sumber_listrik_nilai' => $this->rumah->pilihanSumberListrik->nilai,

            'sumber_air_id' => $this->rumah->pilihanSumberAir->id,
            'sumber_air_nama' => $this->rumah->pilihanSumberAir->nama,
            'sumber_air_nilai' => $this->rumah->pilihanSumberAir->nilai,

            'mck_id' => $this->rumah->pilihanMck->id,
            'mck_nama' => $this->rumah->pilihanMck->nama,
            'mck_nilai' => $this->rumah->pilihanMck->nilai,

            'verifikasi_lapangan_skor' => $this->rumah->verifikasi_lapangan_skor,
            'verifikasi_lapangan_hasil' => $this->rumah->verifikasi_lapangan_hasil,
            'verifikasi_lapangan_catatan' => $this->rumah->verifikasi_lapangan_catatan,
            'skor_akhir' => $this->skor_akhir,

            'listrik_id' => $this->rumah->pilihanListrik->id,
            'listrik_nama' => $this->rumah->pilihanListrik->nama,
            'listrik_nilai' => $this->rumah->pilihanListrik->nilai,

            'kepemilikan_rumah_id' => $this->rumah->pilihanKepemilikanRumah->id,
            'kepemilikan_rumah_nama' => $this->rumah->pilihanKepemilikanRumah->nama,
            'kepemilikan_rumah_nilai' => $this->rumah->pilihanKepemilikanRumah->nilai,

        ];
    }
}
