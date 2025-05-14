<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $mahasiswa = $this;
        $user = $mahasiswa->user;
        $identitas = $user->identitas;
        return [
            'user_id' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'alamat' => $identitas->alamat,
            'foto' => $identitas->foto,
            'jenis_kelamin' => $identitas->jenis_kelamin,
            'desa' => $identitas->desa,
            'kecamatan' => $identitas->kecamatan,
            'kabupaten' => $identitas->kabupaten,
            'provinsi' => $identitas->provinsi,
            'url_id' => $identitas->url_id,
            'nim' => $mahasiswa->nim,
            'kartu_mahasiswa' => $mahasiswa->kartu_mahasiswa,
            'mahasiswa_id' => $mahasiswa->id,
            'sumber_biaya_id' => $mahasiswa->sumber_biaya_id,
            'tahun_masuk' => $mahasiswa->tahun_masuk,
            'program_studi_id' => $mahasiswa->programStudi->id,
            'program_studi' => $mahasiswa->programStudi->nama,
            'program_studi_singkatan' => $mahasiswa->programStudi->singkatan,
            'fakultas_id' => $mahasiswa->programStudi->fakultas->id,
            'fakultas' => $mahasiswa->programStudi->fakultas->nama,
            'fakultas_singkatan' => $mahasiswa->programStudi->fakultas->singkatan,
            'created_at' => $mahasiswa->created_at,
            'updated_at' => $mahasiswa->updated_at,
        ];
    }
}
