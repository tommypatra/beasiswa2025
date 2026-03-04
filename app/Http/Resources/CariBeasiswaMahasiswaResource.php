<?php

namespace App\Http\Resources;

use App\Http\Resources\PenerimaBeasiswaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CariBeasiswaMahasiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $user = $this->user;
        $program_studi = $this->programStudi;
        $fakultas = $program_studi->fakultas;
        return [
            'mahasiswa_id' => $this->id,
            'user_id' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'kartu_mahasiswa' => $this->kartu_mahasiswa,
            'program_studi_id' => $program_studi->id,
            'program_studi_kode' => $program_studi->idprodi,
            'program_studi_singkatan' => $program_studi->singkatan,
            'program_studi' => $program_studi->nama,
            'fakultas_id' => $fakultas->id,
            'fakultas_singkatan' => $fakultas->singkatan,
            'fakultas' => $fakultas->nama,
            'nim' => $this->nim,
            'tahun_masuk' => $this->tahun_masuk,
            'ukt' => $this->ukt,
            'penerima' => PenerimaBeasiswaResource::collection($user->penerima),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
