<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataPendidikanAkhirResource extends JsonResource
{
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
            'pendidikan_akhir_id' => $this->pendidikanAkhir->id,
            'nama_sekolah' => $this->pendidikanAkhir->nama_sekolah,
            'jurusan' => $this->pendidikanAkhir->jurusan,
            'akreditasi' => $this->pendidikanAkhir->akreditasi,
            'nisn' => $this->pendidikanAkhir->nisn,
            'tahun_lulus' => $this->pendidikanAkhir->tahun_lulus,
            'nilai_akhir_lulus' => $this->pendidikanAkhir->nilai_akhir_lulus,

            'verifikasi_lapangan_skor' => $this->pendidikanAkhir->verifikasi_lapangan_skor,
            'verifikasi_lapangan_hasil' => $this->pendidikanAkhir->verifikasi_lapangan_hasil,
            'verifikasi_lapangan_catatan' => $this->pendidikanAkhir->verifikasi_lapangan_catatan,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
