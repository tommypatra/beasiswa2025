<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenerimaResource extends JsonResource
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
            'sk_penerima_id' => $this->sk_penerima_id,
            'penerima_id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'email' => $this->user->email,

            'identitas_id' => $this->user->identitas->id,
            'foto' => $this->user->identitas->foto,
            'jenis_kelamin' => $this->user->identitas->jenis_kelamin,
            'no_hp' => $this->user->identitas->no_hp,
            'tempat_lahir' => $this->user->identitas->tempat_lahir,
            'tanggal_lahir' => $this->user->identitas->tanggal_lahir,
            'alamat' => $this->user->identitas->alamat,
            'desa' => $this->user->identitas->desa,
            'kecamatan' => $this->user->identitas->kecamatan,
            'kabupaten' => $this->user->identitas->kabupaten,
            'provinsi' => $this->user->identitas->provinsi,

            'mahasiswa_id' => $this->user->mahasiswa->id,
            'nim' => $this->user->mahasiswa->nim,
            'kartu_mahasiswa' => $this->user->mahasiswa->kartu_mahasiswa,
            'tahun_masuk' => $this->user->mahasiswa->tahun_masuk,
            'program_studi_id' => $this->user->mahasiswa->programStudi->id,
            'program_studi' => $this->user->mahasiswa->programStudi->nama,
            'fakultas_id' => $this->user->mahasiswa->programStudi->fakultas->id,
            'fakultas' => $this->user->mahasiswa->programStudi->fakultas->nama,
        ];
    }
}
