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
        $pendaftar = $this->pendaftar;
        $mahasiswa = $pendaftar->mahasiswa;
        $user = $mahasiswa->user;
        $program_studi = $mahasiswa->programStudi;
        $fakultas = $program_studi->fakultas;
        return [
            'id' => $this->id,
            'total_skor' => $this->total_skor,
            'hasil' => $this->hasil,
            'catatan' => $this->catatan,
            'verifikator' => $this->verifikator,
            'pendaftar' => [
                'id' => $pendaftar->id,
                'no_pendaftaran' => $pendaftar->no_pendaftaran,
                'is_batal' => $pendaftar->is_batal,
                'is_finalisasi' => $pendaftar->is_finalisasi,
                'url_id' => $pendaftar->url_id,
            ],
            'mahasiswa' => [
                'id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'tahun_masuk' => $mahasiswa->tahun_masuk,
            ],
            'program_studi' => [
                'id' => $program_studi->id,
                'nama' => $program_studi->nama,
                'singkatan' => $program_studi->singkatan,
            ],
            'fakultas' => [
                'id' => $fakultas->id,
                'nama' => $fakultas->nama,
            ],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'foto' => $user->identitas->foto ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
