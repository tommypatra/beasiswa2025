<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\LaporanMahasiswaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DaftarPenerimaVerifikasiResource extends JsonResource
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
        $user = $penerima->user;
        $mahasiswa = $user->mahasiswa;
        $identitas = $user->identitas;
        $program_studi = $mahasiswa->programStudi;
        return [
            'laporan_id' => $this->id,
            'path' => $this->path,
            'penerima_id' => $this->penerima_id,
            'sub_kegiatan_id' => $this->sub_kegiatan_id,
            'verifikasi_hasil' => $this->verifikasi_hasil,
            'user_id' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'nim' => $mahasiswa->nim,
            'program_studi' => $program_studi->nama,
            'fakultas' => $program_studi->fakultas->nama,
            'foto' => $identitas->foto,
            'jenis_kelamin' => $identitas->jenis_kelamin,
            'penerima_id' => $penerima->id,
            'sk_penerima_id' => $penerima->sk_penerima_id,
            'keterangan' => $this->keterangan,
            'sub_kegiatan' => $this->subKegiatan->nama,
            'bukti' => $this->subKegiatan->bukti,
            'keterangan_sub_kegiatan' => $this->subKegiatan->keterangan,
            'nilai' => $this->subKegiatan->nilai,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
