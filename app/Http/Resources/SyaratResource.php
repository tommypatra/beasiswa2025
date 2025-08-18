<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SyaratResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $opsi = [];
        if ($this->instrumen_opsi) {
            $list = array_map('trim', explode(',', $this->instrumen_opsi));
            $skorMax = count($list) - 1;

            foreach ($list as $index => $label) {
                $opsi[] = [
                    'label' => $label,
                    'skor'  => $skorMax - $index
                ];
            }
        }

        return [
            'id'            => $this->id,
            'nama'          => $this->nama,
            'jenis'         => $this->jenis,
            'contoh'        => $this->contoh,
            'bobot'         => $this->bobot,
            'deskripsi'     => $this->deskripsi,
            'is_wajib'      => $this->is_wajib,
            'is_aktif'      => $this->is_aktif,
            'beasiswa_id'   => $this->beasiswa_id,
            'instrumen_opsi' => $this->instrumen_opsi,
            'pilihan_instrumen_opsi' => $opsi,
            'beasiswa' => [
                'id' => $this->beasiswa->id,
                'nama' => $this->beasiswa->nama,
                'tahun' => $this->beasiswa->tahun,
                'kuota' => $this->beasiswa->kuota,
                'daftar_mulai' => $this->beasiswa->daftar_mulai,
                'daftar_selesai' => $this->beasiswa->daftar_selesai
            ],
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
