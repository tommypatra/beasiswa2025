<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenggunaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $identitas = optional($this->identitas);
        //bisa pakai $this->identitas?->id jika blm ada maka null, jika ada ambil id 
        //atau pakai optional di atas biar tidak berulan
        return [
            'user_id' => $this->id,
            'identitas_id' => $this->identitas?->id,
            'email' => $this->email,
            'name' => $this->name,
            'alamat' => $identitas->alamat,
            'desa' => $identitas->desa,
            'jenis_kelamin' => $identitas->jenis_kelamin,
            'foto' => $identitas->foto ?? 'images/user-avatar.png',
            'kabupaten' => $identitas->kabupaten,
            'kecamatan' => $identitas->kecamatan,
            'no_hp' => $identitas->no_hp,
            'provinsi' => $identitas->provinsi,
            'tanggal_lahir' => $identitas->tanggal_lahir,
            'tempat_lahir' => $identitas->tempat_lahir,
            'wilayah_desa_id' => $identitas->wilayah_desa_id,
            'user_role' => UserRoleResource::collection($this->userRole),
        ];
    }
}
