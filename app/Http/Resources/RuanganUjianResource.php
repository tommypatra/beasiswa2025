<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RuanganUjianResource extends JsonResource
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
            'id' => $this->id,
            'beasiswa_id' => $this->beasiswa_id,
            'beasiswa' => $this->beasiswa->nama,
            'tahun' => $this->beasiswa->tahun,
            'kuota' => $this->beasiswa->kuota,
            'ruangan_id' => $this->ruangan_id,
            'ruangan' => $this->ruangan->nama,
            'gedung' => $this->ruangan->gedung,
            'lantai' => $this->ruangan->lantai,
            'kapasitas' => $this->ruangan->kapasitas,
            'keterangan_ruangan' => $this->ruangan->keterangan,
            'urut' => $this->urut,
            'jumlah_peserta' => $this->jumlah_peserta,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
