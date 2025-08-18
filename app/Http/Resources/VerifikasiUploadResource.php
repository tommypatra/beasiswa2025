<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerifikasiUploadResource extends JsonResource
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
            'instrumen_opsi' => $opsi,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'upload_syarat' => $this->uploadSyarat
        ];
    }
}
