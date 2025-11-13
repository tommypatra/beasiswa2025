<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LaporanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $penerima = $this->penerima;
        $sk_penerima = $penerima->skPenerima;
        $user = $penerima->user;

        $mahasiswa = $user->mahasiswa;
        $identitas = $user->identitas;

        $program_studi = $mahasiswa->programStudi;
        $fakultas = $program_studi->fakultas;

        $sub_kegiatan = $this->subKegiatan;
        $kegiatan = $sub_kegiatan->kegiatan;
        return [
            'laporan_id' => $this->id,
            'path' => $this->path,
            'keterangan' => $this->keterangan,
            'path_jenis' => $this->path_jenis,
            'is_kirim' => $this->is_kirim,
            'kegiatan_id' => $kegiatan->id,
            'kegiatan' => $kegiatan->nama,
            'sub_kegiatan_id' => $sub_kegiatan->id,
            'sub_kegiatan' => $sub_kegiatan->nama,
            'sub_kegiatan_skor' => $sub_kegiatan->nilai,
            'keterangan' => $this->keterangan,
            'sk_penerima_id' => $sk_penerima->id,

            'nama' => $this->nama,
            'program_studi' => $program_studi->nama,
            'program_studi_id' => $program_studi->id,
            'fakultas' => $fakultas->nama,
            'fakultas_id' => $fakultas->id,

            'verifikasi_catatan' => $this->verifikasi_catatan,
            'verifikasi_hasil' => $this->verifikasi_hasil,
            'verifikasi_skor' => $this->verifikasi_skor,

            'nama' => $user->name,
            'nim' => $mahasiswa->nim,
            'email' => $user->email,
            'foto' => $identitas->foto,
            'no_hp' => $identitas->no_hp,

            'penerima_id' => $penerima->id,
            'mahasiswa_id' => $mahasiswa->id,
            'user_id' => $user->id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
