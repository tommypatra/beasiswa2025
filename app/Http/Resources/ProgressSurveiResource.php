<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressSurveiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        // Ambil semua peserta survei (jika ada)
        $peserta = $this->surveiPeserta ?? collect();

        // Ambil kabupaten dan kecamatan dari identitas masing-masing peserta
        $kabupatenList = $peserta->map(function ($item) {
            return optional($item->pendaftar->mahasiswa->user->identitas)->kabupaten;
        })
            ->filter()
            ->unique()
            ->values();

        $kecamatanList = $peserta->map(function ($item) {
            return optional($item->pendaftar->mahasiswa->user->identitas)->kecamatan;
        })
            ->filter()
            ->unique()
            ->values();

        return [
            'id' => $this->id,
            'verifikator' => optional($this->user)->name,
            'beasiswa' => optional($this->beasiswa)->nama,
            'total_pendaftar' => $this->total_pendaftar,
            'peserta_valid' => $this->peserta_valid,
            'daftar_kabupaten' => $kabupatenList,
            'daftar_kecamatan' => $kecamatanList,
        ];
    }
}
