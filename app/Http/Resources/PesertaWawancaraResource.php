<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PesertaWawancaraResource extends JsonResource
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
            'is_registrasi_wawancara' => $this->is_registrasi_wawancara,
            'wawancara' => $this->pesertaWawancara,
            'mahasiswa' => $this->mahasiswa,
            'user' => $this->mahasiswa->user,
            'identitas' => $this->mahasiswa->user->identitas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
