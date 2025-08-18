<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PesertaSurveiResource extends JsonResource
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
        $user = $mahasiswa->user;
        $identitas = $user->identitas;
        $survei = $this->surveiPeserta[0];
        return [
            'pendaftar_id' => $this->id,
            'beasiswa_id' => $this->beasiswa_id,
            'beasiswa' => [
                'nama' => $this->beasiswa->nama,
                'tahun' => $this->beasiswa->tahun,
                'perlu_data_orang_tua' => $this->beasiswa->perlu_data_orang_tua,
                'perlu_data_rumah' => $this->beasiswa->perlu_data_rumah,
                'perlu_data_nilai_raport' => $this->beasiswa->perlu_data_nilai_raport,
                'perlu_data_pendidikan_akhir' => $this->beasiswa->perlu_data_pendidikan_akhir,
            ],
            'survei' => [
                'survei_peserta_id' => $survei->id,
                'updated_at' => $survei->updated_at,
                'catatan' => $survei->catatan,
                'hasil' => $survei->hasil,
                'surveyor_id' => $survei->surveyor->id,
                'surveyor' => $survei->surveyor->user->name,
            ],
            'user_id' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'alamat' => $identitas->alamat,
            'foto' => $identitas->foto,
            'jenis_kelamin' => $identitas->jenis_kelamin,
            'desa' => $identitas->desa,
            'kecamatan' => $identitas->kecamatan,
            'kabupaten' => $identitas->kabupaten,
            'no_hp' => $identitas->no_hp,
            'provinsi' => $identitas->provinsi,
            'no_pendaftaran' => $this->no_pendaftaran,
            'url_id' => $identitas->url_id,
            'nim' => $mahasiswa->nim,
            'kartu_mahasiswa' => $mahasiswa->kartu_mahasiswa,
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_masuk' => $mahasiswa->tahun_masuk,
            'program_studi' => $mahasiswa->programStudi->nama,
            'program_studi_singkatan' => $mahasiswa->programStudi->singkatan,
            'fakultas' => $mahasiswa->programStudi->fakultas->nama,
            'fakultas_singkatan' => $mahasiswa->programStudi->fakultas->singkatan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
