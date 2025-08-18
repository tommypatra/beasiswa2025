<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
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
            'user_id' => $this->user_id,
            'role_id' => $this->role_id,
            'user_role_id' => $this->id,
            'keterangan' => $this->role->keterangan,
            'nama' => $this->role->nama
        ];
    }
}
