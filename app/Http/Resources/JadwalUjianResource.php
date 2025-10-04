<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalUjianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $beasiswa = $this->beasiswa ?? null;
        $ruangan_ujian = $this->ruanganUjian ?? null;
        $ruangan = $ruangan_ujian->ruangan ?? null;
        $sesi_ujian = $this->sesiUjian ?? null;

        return [
            'jadwal_ujian_id' => $this->id,
            'beasiswa_id' => $this->beasiswa_id,
            'ruangan_ujian_id' => $this->ruangan_ujian_id,
            'sesi' => $this->sesi,
            'tanggal' => $this->tanggal,
            'sesi_ujian_id' => $this->sesi_ujian_id,

            'jumlah_peserta' => $ruangan_ujian->jumlah_peserta,
            'gedung' => $ruangan->gedung,
            'kapasitas' => $ruangan->kapasitas,
            'keterangan' => $ruangan->keterangan,
            'lantai' => $ruangan->lantai,
            'ruangan' => $ruangan->nama,

            'jam_mulai' => $sesi_ujian->jam_mulai,
            'jam_selesai' => $sesi_ujian->jam_selesai,
            'sesi_master' => $sesi_ujian->sesi,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
