<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkPenerimaResource extends JsonResource
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
        //     'id' => $this->id,
        //     'dokumen' => $this->dokumen,
        //     'pendaftar_id' => $this->pendaftar_id,
        //     'syarat_id' => $this->syarat_id,
        //     'beasiswa_id' => $this->syarat->beasiswa_id,
        //     'contoh' => $this->syarat->contoh,
        //     'deskripsi' => $this->syarat->deskripsi,
        //     'jenis' => $this->syarat->jenis,
        //     'nama' => $this->syarat->nama,
        //     'is_wajib' => $this->syarat->is_wajib,
        //     'is_aktif' => $this->syarat->is_aktif,
        //     'verifikasi_berkas_hasil' => $this->verifikasi_berkas_hasil,
        //     'verifikasi_berkas_catatan' => $this->verifikasi_berkas_catatan,
        //     'verifikasi_berkas_skor' => $this->verifikasi_berkas_skor,
        //     'verifikasi_lapangan_hasil' => $this->verifikasi_lapangan_hasil,
        //     'verifikasi_lapangan_catatan' => $this->verifikasi_lapangan_catatan,
        //     'verifikasi_lapangan_skor' => $this->verifikasi_lapangan_skor,
        // ];
    }
}
