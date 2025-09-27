<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LaporanMahasiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $kegiatan = $this->kegiatan;
        // $sub_kegiatan = $this->subKegiatan;
        return [
            'id' => $this->id,
            'bukti' => $this->bukti,
            'laporan_id' => $this->id,
            'kegiatan_id' => $kegiatan->id,
            'kegiatan' => $kegiatan->nama,
            'keterangan' => $this->keterangan,
            'nama' => $this->nama,
            'nilai' => $this->nilai,
            'laporan' => $this->laporan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
