<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataRaportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $raport = [];
        if ($this->nilaiRaport) {
            $raport = [
                'raport_id' => $this->nilaiRaport->id,
                'smt_1_nilai' => $this->nilaiRaport->smt_1_nilai,
                'smt_1_peringkat' => $this->nilaiRaport->smt_1_peringkat,
                'smt_2_nilai' => $this->nilaiRaport->smt_2_nilai,
                'smt_2_peringkat' => $this->nilaiRaport->smt_2_peringkat,
                'smt_3_nilai' => $this->nilaiRaport->smt_3_nilai,
                'smt_3_peringkat' => $this->nilaiRaport->smt_3_peringkat,
                'smt_4_nilai' => $this->nilaiRaport->smt_4_nilai,
                'smt_4_peringkat' => $this->nilaiRaport->smt_4_peringkat,
                'smt_5_nilai' => $this->nilaiRaport->smt_5_nilai,
                'smt_5_peringkat' => $this->nilaiRaport->smt_5_peringkat,
                'smt_6_nilai' => $this->nilaiRaport->smt_6_nilai,
                'smt_6_peringkat' => $this->nilaiRaport->smt_6_peringkat,

                'verifikasi_lapangan_skor' => $this->nilaiRaport->verifikasi_lapangan_skor,
                'verifikasi_lapangan_hasil' => $this->nilaiRaport->verifikasi_lapangan_hasil,
                'verifikasi_lapangan_catatan' => $this->nilaiRaport->verifikasi_lapangan_catatan,
            ];
        }

        return [
            'user_id' => $this->id,
            'nama' => $this->name,
            'email' => $this->email,
            'akreditasi' => $this->pendidikanAkhir->akreditasi,
            'raport' => $raport,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
