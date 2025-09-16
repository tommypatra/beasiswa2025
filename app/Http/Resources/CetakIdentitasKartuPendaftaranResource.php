<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CetakIdentitasKartuPendaftaranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $beasiswa = $this->beasiswa;
        $mahasiswa = $this->mahasiswa;
        $identitas = $mahasiswa->user->identitas;
        $user = $this->mahasiswa->user;

        $verifikator = $this->verifikatorPendaftar?->verifikator?->user->identitas;
        return [
            'pendaftar_id' => $this->id,
            'url_id' => $this->url_id,
            'no_pendaftaran' => $this->no_pendaftaran,
            'is_finalisasi' => $this->is_finalisasi,
            'updated_at' => $this->updated_at,
            'inisial' => $verifikator?->inisial ?? substr($identitas?->no_hp, -5),
            'nama' => $user?->name,
            'email' => $user?->email,
            'tempat_lahir' => $identitas?->tempat_lahir,
            'tanggal_lahir' => $identitas?->tanggal_lahir,
            'jenis_kelamin' => $identitas?->jenis_kelamin,
            'no_hp' => $identitas?->no_hp,
            'foto' => $identitas?->foto,
            'alamat' => $identitas?->alamat,
            'desa' => $identitas?->desa,
            'kecamatan' => $identitas?->kecamatan,
            'kabupaten' => $identitas?->kabupaten,
            'provinsi' => $identitas?->provinsi,
            'nim' => $mahasiswa?->nim,
            'prodi' => $mahasiswa?->programStudi->nama,
            'fakultas' => $mahasiswa?->programStudi->fakultas->nama,
            // 'no_hp' => $identitas?->no_hp,
            // 'no_hp' => $identitas?->no_hp,
            // 'no_hp' => $identitas?->no_hp,
        ];
    }
}
