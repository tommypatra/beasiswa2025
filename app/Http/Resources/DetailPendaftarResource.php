<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPendaftarResource extends JsonResource
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
        $syarat = $this->beasiswa->syarat;
        $mahasiswa = $this->mahasiswa;
        $user = $this->mahasiswa->user;
        $identitas = $this->mahasiswa->user->identitas;
        $program_studi = $this->mahasiswa->programStudi;
        $faultas = $program_studi->fakultas;
        return [
            'pendaftar_id' => $this->id,
            'alasan' => $this->alasan,
            'is_batal' => $this->is_batal,
            'is_finalisasi' => $this->is_finalisasi,
            'no_pendaftaran' => $this->no_pendaftaran,
            'url_id' => $this->url_id,
            'is_registrasi_ujian' => $this->is_registrasi_ujian,
            'is_registrasi_wawancara' => $this->is_registrasi_wawancara,
            'beasiswa' => [
                'id' => $beasiswa->id,
                'tahun' => $beasiswa->tahun,
                'nama' => $beasiswa->nama,
                'kuota' => $beasiswa->kuota,
                'is_pendaftaran_aktif' => $beasiswa->is_pendaftaran_aktif,
            ],
            'syarat' => $syarat,
            'mahasiswa' => [
                'id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'tahun_masuk' => $mahasiswa->tahun_masuk,
                'kartu_mahasiswa' => $mahasiswa->kartu_mahasiswa,
                'ukt' => $mahasiswa->ukt,
                'program_studi_nama' => $program_studi->nama,
                'program_studi_id' => $program_studi->id,
                'program_studi_singkatan' => $program_studi->singkatan,
                'program_studi_idprodi' => $program_studi->idprodi,
                'fakultas_id' => $faultas->id,
                'fakultas' => $program_studi->nama,
                'fakultas_singkatan' => $program_studi->singkatan,
            ],
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_name' => $user->name,
            'identitas' => $identitas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
