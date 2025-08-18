<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifikatorLaporanResource extends JsonResource
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
            'verifikator_laporan_id' => $this->id,
            'sk_penerima_id' => $this->sk_penerima_id,
            'perihal_sk' => $this->skPenerima->nama,
            'nomor_sk' => $this->skPenerima->nomor_sk,
            'tanggal_sk' => $this->skPenerima->tanggal_sk,
            'ttd_jabatan' => $this->skPenerima->ttd_jabatan,
            'ttd_nama' => $this->skPenerima->ttd_nama,
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'email' => $this->user->email
        ];
    }
}
