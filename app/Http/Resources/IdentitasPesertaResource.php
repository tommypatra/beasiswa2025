<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdentitasPesertaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hasil' => $this->hasil,
            'catatan' => $this->catatan,
            'verifikator' => $this->verifikator,
            'pendaftar' => [
                'id' => $this->pendaftar->id,
                'no_pendaftaran' => $this->pendaftar->no_pendaftaran,
                'is_batal' => $this->pendaftar->is_batal,
                'is_finalisasi' => $this->pendaftar->is_finalisasi,
                'url_id' => $this->pendaftar->url_id,
            ],
            'mahasiswa' => [
                'id' => $this->pendaftar->mahasiswa->id,
                'nim' => $this->pendaftar->mahasiswa->nim,
                'tahun_masuk' => $this->pendaftar->mahasiswa->tahun_masuk,
            ],
            'program_studi' => [
                'id' => $this->pendaftar->mahasiswa->programStudi->id,
                'nama' => $this->pendaftar->mahasiswa->programStudi->nama,
                'singkatan' => $this->pendaftar->mahasiswa->programStudi->singkatan,
            ],
            'user' => [
                'id' => $this->pendaftar->mahasiswa->user->id,
                'name' => $this->pendaftar->mahasiswa->user->name,
                'email' => $this->pendaftar->mahasiswa->user->email,
            ],
            'foto' => $this->pendaftar->mahasiswa->user->identitas->foto ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
