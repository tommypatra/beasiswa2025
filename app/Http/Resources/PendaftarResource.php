<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendaftarResource extends JsonResource
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
        //     'beasiswa' => [
        //         'id' => $this->beasiswa->id,
        //         'nama' => $this->beasiswa->nama,
        //         'tahun' => $this->beasiswa->tahun,
        //         'jenis' => $this->beasiswa->jenisBeasiswa->nama,
        //         'is_pendaftaran_aktif' => $this->beasiswa->is_pendaftaran_aktif,
        //     ],
        //     'syarat' => $this->syarat,
        //     'mahasiswa' => [
        //         'id' => $this->mahasiswa->id,
        //         'nim' => $this->mahasiswa->nim,
        //         'kartu_mahasiswa' => $this->mahasiswa->kartu_mahasiswa,
        //         'tahun_masuk' => $this->mahasiswa->tahun_masuk,
        //         'program_studi' => $this->mahasiswa->programStudi->nama,
        //         'fakultas' => $this->mahasiswa->programStudi->Fakultas->nama,
        //     ],
        //     'pendaftar' => [
        //         'id' => $this->id,
        //         'no_pendaftaran' => $this->no_pendaftaran,
        //         'url_id' => $this->url_id,
        //         'is_batal' => $this->is_batal,
        //         'is_finalisasi' => $this->is_finalisasi,
        //         'is_registrasi_ujian' => $this->is_registrasi_ujian,
        //         'is_registrasi_wawancara' => $this->is_registrasi_wawancara,
        //     ],
        //     'user' => [
        //         'id' => $this->mahasiswa->user->id,
        //         'name' => $this->mahasiswa->user->name,
        //         'email' => $this->mahasiswa->user->email,
        //     ],
        //     'identitas' => [
        //         'id' => $this->mahasiswa->user->identitas->id,
        //         'alamat' => $this->mahasiswa->user->identitas->alamat,
        //         'jenis_kelamin' => $this->mahasiswa->user->identitas->jenis_kelamin,
        //         'no_hp' => $this->mahasiswa->user->identitas->no_hp,
        //         'tanggal_lahir' => $this->mahasiswa->user->identitas->tanggal_lahir,
        //         'tempat_lahir' => $this->mahasiswa->user->identitas->tempat_lahir,
        //         'kelurahan' => $this->mahasiswa->user->identitas->wilayahDesa->nama,
        //         'kecamatan' => $this->mahasiswa->user->identitas->wilayahDesa->wilayahKecamatan->nama,
        //         'kabupaten' => $this->mahasiswa->user->identitas->wilayahDesa->wilayahKecamatan->wilayahKabupaten->nama,
        //         'provinsi' => $this->mahasiswa->user->identitas->wilayahDesa->wilayahKecamatan->wilayahKabupaten->wilayahProvinsi->nama,
        //     ],
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        // ];
    }
}
