<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PesertaUjianBeasiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $mahasiswa = $this->mahasiswa;
        $program_studi = $mahasiswa->programStudi;
        $fakultas = $program_studi->fakultas;
        $user = $mahasiswa->user;
        $identitas = $user->identitas;
        return [
            'pendaftar_id' => $this->id,
            'beasiswa_id' => $this->beasiswa_id,
            'mahasiswa_id' => $mahasiswa->id,
            'nim' => $mahasiswa->nim,
            'program_studi' => $program_studi->nama,
            'fakultas' => $program_studi->fakultas->nama,
            'foto' => $identitas->foto,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'no_pendaftaran' => $this->no_pendaftaran,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
