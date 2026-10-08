<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RincianDetailPendaftarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $mahasiswa = $this->mahasiswa;
        $user = $mahasiswa?->user;
        $identitas = $user?->identitas;
        $orangTua = $user?->orangTua;
        $rumah = $user?->rumah;
        $pendidikan = $user?->pendidikanAkhir;
        $raport = $user?->nilaiRaport;

        return [

            // =========================================================
            // PENDAFTARAN
            // =========================================================
            'pendaftaran' => [
                'id' => $this->id,
                'no_pendaftaran' => $this->no_pendaftaran,
                'url_id' => $this->url_id,
                'tag' => $this->tag,
                'is_batal' => $this->is_batal,
                'is_finalisasi' => $this->is_finalisasi,
                'is_registrasi_wawancara' => $this->is_registrasi_wawancara,
                'is_registrasi_ujian' => $this->is_registrasi_ujian,
                'alasan' => $this->alasan,
            ],


            // =========================================================
            // BEASISWA
            // =========================================================
            'beasiswa' => [
                'id' => $this->beasiswa?->id,
                'nama' => $this->beasiswa?->nama,

                'jenis' => [
                    'id' => $this->beasiswa?->jenisBeasiswa?->id,
                    'nama' => $this->beasiswa?->jenisBeasiswa?->nama,
                ],
            ],


            // =========================================================
            // MAHASISWA
            // =========================================================
            'mahasiswa' => [
                'id' => $mahasiswa?->id,
                'nim' => $mahasiswa?->nim,
                'nama' => $user?->name,
                'email' => $user?->email,

                'tahun_masuk' => $mahasiswa?->tahun_masuk,
                'ukt' => $mahasiswa?->ukt,

                'program_studi' => [
                    'id' => $mahasiswa?->programStudi?->id,
                    'nama' => $mahasiswa?->programStudi?->nama,
                ],

                'fakultas' => [
                    'id' => $mahasiswa?->programStudi?->fakultas?->id,
                    'nama' => $mahasiswa?->programStudi?->fakultas?->nama,
                ],
            ],


            // =========================================================
            // IDENTITAS
            // =========================================================
            'identitas' => $identitas ? [
                'tempat_lahir' => $identitas->tempat_lahir,
                'tanggal_lahir' => $identitas->tanggal_lahir,
                'jenis_kelamin' => $identitas->jenis_kelamin,
                'no_hp' => $identitas->no_hp,
                'foto' => $identitas->foto,

                'alamat' => [
                    'alamat' => $identitas->alamat,
                    'desa' => $identitas->desa,
                    'kecamatan' => $identitas->kecamatan,
                    'kabupaten' => $identitas->kabupaten,
                    'provinsi' => $identitas->provinsi,
                ],

                'disabilitas' => $identitas->disabilitas,
            ] : null,


            // =========================================================
            // ORANG TUA
            // =========================================================
            'orang_tua' => $orangTua ? [

                'bapak' => [
                    'nama' => $orangTua->bapak_nama,
                    'status_hidup' => $orangTua->status_hidup_bapak_kandung,

                    'pekerjaan' => $orangTua->pekerjaanBapak?->nama,
                    'pendidikan' => $orangTua->pendidikanBapak?->nama,
                    'pendapatan' => $orangTua->pendapatanBapak?->nama,
                ],

                'ibu' => [
                    'nama' => $orangTua->ibu_nama,
                    'status_hidup' => $orangTua->status_hidup_ibu_kandung,

                    'pekerjaan' => $orangTua->pekerjaanIbu?->nama,
                    'pendidikan' => $orangTua->pendidikanIbu?->nama,
                    'pendapatan' => $orangTua->pendapatanIbu?->nama,
                ],

                'tanggungan' => $orangTua->tanggungan,

                'verifikasi' => [
                    'hasil' => $orangTua->verifikasi_lapangan_hasil,
                    'catatan' => $orangTua->verifikasi_lapangan_catatan,
                    'skor' => $orangTua->verifikasi_lapangan_skor,
                ],

            ] : null,


            // =========================================================
            // RUMAH
            // =========================================================
            'rumah' => $rumah ? [

                'luas_tanah' => $rumah->luas_tanah,
                'luas_bangunan' => $rumah->luas_bangunan,
                'jumlah_orang_tinggal' => $rumah->jumlah_orang_tinggal,

                'kepemilikan' =>
                    $rumah->pilihanKepemilikanRumah?->nama,

                'mck' =>
                    $rumah->pilihanMck?->nama,

                'listrik' =>
                    $rumah->pilihanListrik?->nama,

                'sumber_air' =>
                    $rumah->pilihanSumberAir?->nama,

                'sumber_listrik' =>
                    $rumah->pilihanSumberListrik?->nama,

                'foto' => $rumah->foto_rumah,

                'verifikasi' => [
                    'hasil' => $rumah->verifikasi_lapangan_hasil,
                    'catatan' => $rumah->verifikasi_lapangan_catatan,
                    'skor' => $rumah->verifikasi_lapangan_skor,
                ],

            ] : null,


            // =========================================================
            // PENDIDIKAN TERAKHIR
            // =========================================================
            'pendidikan_akhir' => $pendidikan ? [

                'nisn' => $pendidikan->nisn,
                'nama_sekolah' => $pendidikan->nama_sekolah,
                'jenis' => $pendidikan->jenis,
                'akreditasi' => $pendidikan->akreditasi,
                'tahun_lulus' => $pendidikan->tahun_lulus,
                'nilai_akhir' => $pendidikan->nilai_akhir_lulus,
                'jurusan' => $pendidikan->jurusan,
                'foto_ijazah' => $pendidikan->foto_ijazah,

                'verifikasi' => [
                    'hasil' => $pendidikan->verifikasi_lapangan_hasil,
                    'catatan' => $pendidikan->verifikasi_lapangan_catatan,
                    'skor' => $pendidikan->verifikasi_lapangan_skor,
                ],

            ] : null,


            // =========================================================
            // RAPORT
            // =========================================================
            'raport' => $raport ? [

                'semester_1' => [
                    'nilai' => $raport->smt_1_nilai,
                    'peringkat' => $raport->smt_1_peringkat,
                    'foto' => $raport->foto_raport_smt_1,
                ],

                'semester_2' => [
                    'nilai' => $raport->smt_2_nilai,
                    'peringkat' => $raport->smt_2_peringkat,
                    'foto' => $raport->foto_raport_smt_2,
                ],

                'semester_3' => [
                    'nilai' => $raport->smt_3_nilai,
                    'peringkat' => $raport->smt_3_peringkat,
                    'foto' => $raport->foto_raport_smt_3,
                ],

                'semester_4' => [
                    'nilai' => $raport->smt_4_nilai,
                    'peringkat' => $raport->smt_4_peringkat,
                    'foto' => $raport->foto_raport_smt_4,
                ],

                'semester_5' => [
                    'nilai' => $raport->smt_5_nilai,
                    'peringkat' => $raport->smt_5_peringkat,
                    'foto' => $raport->foto_raport_smt_5,
                ],

                'semester_6' => [
                    'nilai' => $raport->smt_6_nilai,
                    'peringkat' => $raport->smt_6_peringkat,
                    'foto' => $raport->foto_raport_smt_6,
                ],

            ] : null,


            // =========================================================
            // DOKUMEN
            // =========================================================
            'dokumen' => $this->uploadSyarat
                ->map(function ($item) {
                    return [
                        'id' => $item->id,

                        'nama' => $item->syarat?->nama,

                        'jenis' => $item->syarat?->jenis,

                        'dokumen' => $item->dokumen,

                        'verifikasi' => [
                            'hasil' => $item->verifikasi_berkas_hasil,
                            'catatan' => $item->verifikasi_berkas_catatan,
                            'skor' => $item->verifikasi_berkas_skor,
                        ],
                    ];
                })
                ->values(),


            // =========================================================
            // KELULUSAN / NILAI
            // =========================================================
            'kelulusan' => $this->kelulusan ? [

                'nilai' => [
                    'survei' => $this->kelulusan->nilai_survei,
                    'cbt' => $this->kelulusan->nilai_cbt,
                    'berkas' => $this->kelulusan->nilai_berkas,
                    'orang_tua' => $this->kelulusan->nilai_orang_tua,
                    'raport' => $this->kelulusan->nilai_raport,
                    'pendidikan_akhir' => $this->kelulusan->nilai_pendidikan_akhir,
                    'rumah' => $this->kelulusan->nilai_rumah,
                    'wawancara' => $this->kelulusan->nilai_wawancara,
                    'ekonomi' => $this->kelulusan->nilai_ekonomi,
                    'pendidikan' => $this->kelulusan->nilai_pendidikan,
                ],

                'is_lulus' => $this->kelulusan->is_lulus,
                'catatan' => $this->kelulusan->catatan,
            ] : null,
        ];
    }
}
