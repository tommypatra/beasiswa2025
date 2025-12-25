<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CetakPesertaUjianResource extends JsonResource
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
        $program_studi = $mahasiswa->programStudi;
        $fakultas = $program_studi->fakultas;
        $mahasiswa = $pendaftar->mahasiswa;
        $user_mahasiswa = $mahasiswa->user;
        $idenitas_mahasiswa = $user_mahasiswa->identitas;

        return [
            'peserta_ujian_id' => $this->id,
            'jadwal_ujian_id' => $this->jadwal_ujian_id,
            'pendaftar_id' => $this->pendaftar_id,
            'nilai' => $this->nilai,
            'status' => $this->status,
            'jadwal' => $this->jadwalUjian,
            'mahasiswa' => [
                'mahasiswa_id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'program_studi_id' => $program_studi->id,
                'program_studi' => $program_studi->nama,
                'program_studi_singkatan' => $program_studi->singkatan,
                'fakultas_id' => $fakultas->id,
                'fakultas' => $fakultas->nama,
                'fakultas_singkatan' => $fakultas->singkatan,
                'user_id' => $user_mahasiswa->id,
                'nama' => $user_mahasiswa->name,
                'foto' => $idenitas_mahasiswa->foto,
                'email' => $user_mahasiswa->email,
                'jenis_kelamin' => $idenitas_mahasiswa->jenis_kelamin,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
