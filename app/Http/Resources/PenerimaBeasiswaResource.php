<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenerimaBeasiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $sk_penerima = $this->skPenerima;
        return [
            'penerima_id' => $this->id,
            'sk_penerima_id' => $sk_penerima?->id,
            'keterangan' => $this->keterangan,
            'nama' => $sk_penerima?->nama,
            'nomor_sk' => $sk_penerima?->nomor_sk,
            'tanggal_sk' => $sk_penerima?->tanggal_sk,
            'ttd_jabatan' => $sk_penerima?->ttd_jabatan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
