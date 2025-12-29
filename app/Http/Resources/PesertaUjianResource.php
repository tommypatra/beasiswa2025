<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PesertaUjianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $jadwal_ujian = $this->jadwalUjian;
        $beasiswa = $jadwal_ujian->beasiswa;
        $sesi_ujian = $jadwal_ujian->sesiUjian;
        $ruangan = $jadwal_ujian->ruanganUjian->ruangan;
        $pendaftar = $this->pendaftar;

        $mahasiswa = $pendaftar->mahasiswa;
        $program_studi = $mahasiswa->programStudi;
        $fakultas = $program_studi->fakultas;
        $user = $mahasiswa->user;
        $identitas = $user->identitas;
        return [
            'peserta_ujian_id' => $this->id,
            'pendaftar_id' => $this->pendaftar_id,
            'beasiswa_id' => $beasiswa->id,
            'url_id' => $pendaftar->url_id,
            'beasiswa' => [
                'nama' => $beasiswa->nama,
                'tahun' => $beasiswa->tahun,
                'perlu_data_orang_tua' => $beasiswa->perlu_data_orang_tua,
                'perlu_data_rumah' => $beasiswa->perlu_data_rumah,
                'perlu_data_nilai_raport' => $beasiswa->perlu_data_nilai_raport,
                'perlu_data_pendidikan_akhir' => $beasiswa->perlu_data_pendidikan_akhir,
                'is_survei_aktif' => $beasiswa->is_survei_aktif,
            ],
            'jadwal_ujian' => [
                'id' => $jadwal_ujian->id,
                'sesi' => $jadwal_ujian->sesi,
                'tanggal' => $jadwal_ujian->tanggal,
                'sesi_ujian_id' => $jadwal_ujian->sesi_ujian_id,
                'ruangan_id' => $ruangan->id,
                'gedung' => $ruangan->gedung,
                'kapasitas' => $ruangan->kapasitas,
                'nama' => $ruangan->nama,
                'lantai' => $ruangan->lantai,
                'keterangan' => $ruangan->keterangan,
            ],
            'sesi_ujian' => $sesi_ujian,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'alamat' => $identitas->alamat,
            'foto' => $identitas->foto,
            'jenis_kelamin' => $identitas->jenis_kelamin,
            'desa' => $identitas->desa,
            'kecamatan' => $identitas->kecamatan,
            'tempat_lahir' => $identitas->tempat_lahir,
            'tanggal_lahir' => $identitas->tanggal_lahir,
            'kabupaten' => $identitas->kabupaten,
            'provinsi' => $identitas->provinsi,
            'no_hp' => $identitas->no_hp,
            'link_wa' => linkwa($identitas->no_hp),
            'no_pendaftaran' => $pendaftar->no_pendaftaran,
            'nim' => $mahasiswa->nim,
            'kartu_mahasiswa' => $mahasiswa->kartu_mahasiswa,
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_masuk' => $mahasiswa->tahun_masuk,
            'program_studi' => $mahasiswa->programStudi->nama,
            'program_studi_singkatan' => $mahasiswa->programStudi->singkatan,
            'fakultas' => $mahasiswa->programStudi->fakultas->nama,
            'fakultas_singkatan' => $mahasiswa->programStudi->fakultas->singkatan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'waktu_daftar' => $pendaftar->created_at,
        ];
    }
}
