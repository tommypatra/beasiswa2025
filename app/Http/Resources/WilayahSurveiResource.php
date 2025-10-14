<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WilayahSurveiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        // return [
        //     'provinsi' => $this['provinsi'],
        //     'kabupaten' => $this['kabupaten'],
        //     'kecamatan' => $this['kecamatan'],
        // ];
    }
}
