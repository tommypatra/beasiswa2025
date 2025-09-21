<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataPesertaLulusResource extends JsonResource
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
        $user = $mahasiswa->user;
        $penerima = $user?->penerima->isNotEmpty() ?? false;
        $identitas = $user->identitas;
        $programStudi = $mahasiswa->programStudi;
        $fakultas = $programStudi ? $programStudi->fakultas : null;

        return [
            'id' => $this->id,
            'pendaftar_id' => $this->pendaftar_id,
            'no_pendaftaran' => $pendaftar->no_pendaftaran,
            'user_id' => $user->id,
            'mahasiswa' => [
                'nama' => $user->name,
                'email' => $user->email,
                'tempat_lahir' => $identitas->tempat_lahir ?? null,
                'no_hp' => $identitas->no_hp ?? null,
                'nim' => $mahasiswa->nim ?? null,
                'tanggal_lahir' => $identitas->tanggal_lahir ?? null,
                'jenis_kelamin' => $identitas->jenis_kelamin ?? null,
                'alamat' => $identitas->alamat ?? null,
                'desa' => $identitas->desa ?? null,
                'kecamatan' => $identitas->kecamatan ?? null,
                'kabupaten' => $identitas->kabupaten ?? null,
                'provinsi' => $identitas->provinsi ?? null,
                'program_studi' => $programStudi->nama ?? null,
                'fakultas' => $fakultas->nama ?? null,
            ],
            'nilai' => [
                'survei' => (float) $this->nilai_survei,
                'cbt' => $this->nilai_cbt !== null ? (float) $this->nilai_cbt : null,
                'berkas' => (float) $this->nilai_berkas,
                'orang_tua' => (float) $this->nilai_orang_tua,
                'raport' => (float) $this->nilai_raport,
                'pendidikan_akhir' => (float) $this->nilai_pendidikan_akhir,
                'rumah' => (float) $this->nilai_rumah,
                'wawancara' => (float) $this->nilai_wawancara,
                'ekonomi' => (float) $this->nilai_ekonomi,
                'pendidikan' => (float) $this->nilai_pendidikan,
            ],
            'status' => [
                'is_lulus' => $this->is_lulus,
                'catatan' => $this->catatan,
            ],
            'is_terdata_sk' => $penerima,
        ];
    }
}
