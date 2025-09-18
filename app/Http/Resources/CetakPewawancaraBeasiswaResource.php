<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CetakPewawancaraBeasiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $pendaftar = $this->pendaftar;
        $mahasiswa = $pendaftar->mahasiswa;
        $program_studi = $mahasiswa->programStudi;
        $fakultas = $program_studi->fakultas;
        $mahasiswa = $pendaftar->mahasiswa;
        $user_mahasiswa = $mahasiswa->user;
        $idenitas_mahasiswa = $user_mahasiswa->identitas;
        $pewawancara = $this->pewawancara;
        $user_pewawancara = $pewawancara->user;

        $nilai_wawancara = collect($this->wawancaraNilai ?? [])
            ->where('pewawancara_id', $this->pewawancara_id) // filter sesuai pewawancara di baris ini
            ->map(function ($dt) {
                return [
                    'nilai' => $dt->nilai,
                    'catatan' => $dt->catatan,
                    'soal' => $dt->soalWawancara->soal ?? null,
                    'nomor' => $dt->soalWawancara->nomor ?? null,
                    'persentase_nilai' => $dt->soalWawancara->persentase_nilai ?? null,
                ];
            })
            ->values();



        return [
            'peserta_wawancara_id' => $this->id,
            'pewawancara_id' => $this->pewawancara_id,
            'pendaftar_id' => $this->pendaftar_id,
            'nilai' => $this->nilai,
            'status' => $this->status,
            'mahasiswa' => [
                'mahasiswa_id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'program_studi_id' => $program_studi->id,
                'program_studi' => $program_studi->nama,
                'program_studi_singkatan' => $program_studi->singkatan,
                'fakultas_id' => $fakultas->id,
                'fakultas' => $fakultas->nama,
                'fakultas_singkatan' => $program_studi->singkatan,
                'user_id' => $user_mahasiswa->id,
                'nama' => $user_mahasiswa->name,
                'email' => $user_mahasiswa->email,
                'jenis_kelamin' => $idenitas_mahasiswa->jenis_kelamin,
            ],
            'pewawancara' => [
                'pewawancara_id' => $pewawancara->id,
                'nama' => $user_pewawancara->name,
                'email' => $user_pewawancara->email,
                'user_id' => $user_pewawancara->id,
            ],
            'hasil_wawancara' => $nilai_wawancara,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
