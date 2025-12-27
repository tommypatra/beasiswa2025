<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MateriUjianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $beasiswa = $this->beasiswa;
        return [
            'id' => $this->id,
            'ujian' => $this->ujian,
            'urut' => $this->urut,
            'keterangan' => $this->keterangan,
            'beasiswa_id' => $this->beasiswa_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'beasiswa' => $beasiswa->nama,
            'tahun' => $beasiswa->tahun,
        ];
    }
}
