<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DaftarPendaftarResource extends JsonResource
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
        $kelulusan = $this->kelulusan;
        $mahasiswa = $this->mahasiswa;
        $identitas = $this->mahasiswa->user->identitas;
        $verifikasi = $this->verifikatorPendaftar;
        $verifikator = $this->verifikatorPendaftar?->verifikator?->user;

        $pendidikan_akhir = $this->mahasiswa->user->pendidikanAkhir;
        $user = $this->mahasiswa->user;
        return [
            'pendaftar_id' => $this->id,
            'user_id' => $user->id,
            'identitas_id' => $identitas->id,
            'mahasiswa_id' => $mahasiswa->id,
            'nama' => $user->name,
            'sekolah' => $pendidikan_akhir?->nama,
            'jenis_sekolah' => $pendidikan_akhir?->jenis,
            'nisn' => $pendidikan_akhir?->nisn,
            'tahun_lulus_sekolah' => $pendidikan_akhir?->tahun_lulus,
            'jurusan_sekolah' => $pendidikan_akhir?->jurusan,
            'akreditasi_sekolah' => $pendidikan_akhir?->akreditasi,
            'nim' => $mahasiswa->nim,
            'tahun_masuk' => $mahasiswa->tahun_masuk,
            'alamat' => $identitas->alamat,
            'desa' => $identitas->desa,
            'jenis_kelamin' => $identitas->jenis_kelamin,
            'kabupaten' => $identitas->kabupaten,
            'kecamatan' => $identitas->kecamatan,
            'provinsi' => $identitas->provinsi,
            'tanggal_lahir' => $identitas->tanggal_lahir,
            'tempat_lahir' => $identitas->tempat_lahir,
            'no_hp' => $identitas->no_hp,
            'foto' => $identitas->foto,
            'ukt' => $mahasiswa->ukt,
            'program_studi' => $mahasiswa->programStudi?->nama,
            'singkatan_program_studi' => $mahasiswa->programStudi?->singkatan,
            'fakultas' => $mahasiswa->programStudi?->fakultas?->nama,
            'email' => $user->email,
            'beasiswa' => $beasiswa?->nama,
            'jenis_beasiswa' => $beasiswa?->jenisBeasiswa?->nama,
            'beasiswa_id' => $beasiswa->id,
            'tahun_beasiswa' => $beasiswa->tahun,
            'kuota_beasiswa' => $beasiswa->kuota,
            'is_lulus' => $kelulusan?->is_lulus,
            'no_pendaftaran' => $this->no_pendaftaran,
            'is_finalisasi' => $this->is_finalisasi,
            'is_batal' => $this->is_batal,
            'is_registrasi_ujian' => $this->is_registrasi_ujian,
            'is_registrasi_wawancara' => $this->is_registrasi_wawancara,
            'alasan_batal' => $this->alasan,
            'catatan_verifikasi' => $verifikasi?->catatan,
            'hasil_verifikasi' => $verifikasi?->hasil,
            'nilai_verifikasi' => $verifikasi?->total_skor,
            'verifikator' => $verifikator?->name,
        ];
    }
}
