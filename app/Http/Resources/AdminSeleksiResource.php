<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminSeleksiResource extends JsonResource
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
        $beasiswa['pendaftar_count'] = $this->pendaftar_count;
        $user = $this->user;
        $identitas = $user->identitas;
        return [
            'beasiswa_id' => $beasiswa->id,
            'beasiswa' => $beasiswa,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'alamat' => $identitas->alamat ?? null,
            'identitas_id' => $identitas->id ?? null,
            'desa' => $identitas->desa ?? null,
            'kabupaten' => $identitas->kabupaten ?? null,
            'kecamatan' => $identitas->kecamatan ?? null,
            'provinsi' => $identitas->provinsi ?? null,
            'foto' => $identitas->foto ?? null,
            'jenis_kelamin' => $identitas->jenis_kelamin ?? null,
            'no_hp' => $identitas->no_hp ?? null,
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
