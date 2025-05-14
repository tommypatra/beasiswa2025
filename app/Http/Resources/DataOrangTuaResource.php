<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataOrangTuaResource extends JsonResource
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
            'tanggungan' => $this->orangTua->tanggungan,
            'jumlah_pilihan' => $this->jumlahPilihan,

            // 'luas_tanah' => $this->rumah->luas_tanah,
            // 'luas_bangunan' => $this->rumah->luas_bangunan,
            // 'jumlah_orang_tinggal' => $this->rumah->jumlah_orang_tinggal,

            // 'listrik' =>  [
            //     'id' => $this->rumah->pilihanListrik->id,
            //     'nama' => $this->rumah->pilihanListrik->nama,
            //     'nilai' => $this->rumah->pilihanListrik->nilai,
            // ],
            // 'sumber_listrik' =>  [
            //     'id' => $this->rumah->pilihanSumberListrik->id,
            //     'nama' => $this->rumah->pilihanSumberListrik->nama,
            //     'nilai' => $this->rumah->pilihanSumberListrik->nilai,
            // ],
            // 'mck' => [
            //     'id' => $this->rumah->pilihanMck->id,
            //     'nama' => $this->rumah->pilihanMck->nama,
            //     'nilai' => $this->rumah->pilihanMck->nilai,
            // ],
            // 'sumber_air' => [
            //     'id' => $this->rumah->pilihanSumberAir->id,
            //     'nama' => $this->rumah->pilihanSumberAir->nama,
            //     'nilai' => $this->rumah->pilihanSumberAir->nilai,
            // ],
            // 'kepemilikan_rumah' => [
            //     'id' => $this->rumah->pilihanKepemilikanRumah->id,
            //     'nama' => $this->rumah->pilihanKepemilikanRumah->nama,
            //     'nilai' => $this->rumah->pilihanKepemilikanRumah->nilai,
            // ],
            'orang_tua_id' => $this->orangTua->id,
            'verifikasi_lapangan_skor' => $this->orangTua->verifikasi_lapangan_skor,
            'verifikasi_lapangan_hasil' => $this->orangTua->verifikasi_lapangan_hasil,
            'verifikasi_lapangan_catatan' => $this->orangTua->verifikasi_lapangan_catatan,

            'bapak' => [
                'nama' => $this->orangTua->bapak_nama,
                'pekerjaan_id' => $this->orangTua->pekerjaanBapak->id,
                'pekerjaan' => $this->orangTua->pekerjaanBapak->nama,
                'pekerjaan_nilai' => $this->orangTua->pekerjaanBapak->nilai,
                'pendidikan_id' => $this->orangTua->pendidikanBapak->id,
                'pendidikan' => $this->orangTua->pendidikanBapak->nama,
                'pendidikan_nilai' => $this->orangTua->pendidikanBapak->nilai,
                'pendapatan_id' => $this->orangTua->pendapatanBapak->id,
                'pendapatan' => $this->orangTua->pendapatanBapak->nama,
                'pendapatan_nilai' => $this->orangTua->pendapatanBapak->nilai,
                'status' => $this->orangTua->status_hidup_bapak_kandung,
            ],
            'ibu' => [
                'nama' => $this->orangTua->ibu_nama,
                'pekerjaan_id' => $this->orangTua->pekerjaanIbu->id,
                'pekerjaan' => $this->orangTua->pekerjaanIbu->nama,
                'pekerjaan_nilai' => $this->orangTua->pekerjaanIbu->nilai,
                'pendidikan_id' => $this->orangTua->pendidikanIbu->id,
                'pendidikan' => $this->orangTua->pendidikanIbu->nama,
                'pendidikan_nilai' => $this->orangTua->pendidikanIbu->nilai,
                'pendapatan_id' => $this->orangTua->pendapatanIbu->id,
                'pendapatan' => $this->orangTua->pendapatanIbu->nama,
                'pendapatan_nilai' => $this->orangTua->pendapatanIbu->nilai,
                'status' => $this->orangTua->status_hidup_ibu_kandung,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
