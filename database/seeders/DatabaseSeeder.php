<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Syarat;
use App\Models\Beasiswa;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Fakultas;
use App\Models\UserRole;
use App\Models\Identitas;
use App\Models\ProgramStudi;
use App\Models\JenisBeasiswa;
use Illuminate\Database\Seeder;
use App\Models\ReferensiPilihan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //role 
        $dtdef = [
            ['nama' => 'Admin'],
            ['nama' => 'Mahasiswa'],
            ['nama' => 'Surveyor'],
            ['nama' => 'Verifikator'],
            ['nama' => 'Pewawancara'],
            ['nama' => 'Pengelola'],
        ];

        foreach ($dtdef as $dt) {
            Role::create([
                'nama' => $dt['nama'],
            ]);
        }

        //buat user
        $dtdef = [
            ['name' => 'Admin', 'email' => 'tommyirawan.patra@gmail.com'],
            ['name' => 'Aleesya', 'email' => 'aleesya@app.com'],
            ['name' => 'Al Fath', 'email' => 'alfath@app.com'],
            ['name' => 'Arumi', 'email' => 'arumi@app.com'],
        ];

        foreach ($dtdef as $dt) {
            User::create([
                'name' => $dt['name'],
                'email' => $dt['email'],
                'password' => Hash::make('00000000'),
            ]);
        }

        //identitas user
        $dtdef = [
            ['user_id' => 1, 'tempat_lahir' => 'Kendari', 'tanggal_lahir' => '2005-01-01', 'jenis_kelamin' => 'L', 'no_hp' => '085332341653', 'alamat' => 'BTN Rizky'],
            ['user_id' => 2, 'tempat_lahir' => 'Ranomeeto', 'tanggal_lahir' => '2004-12-23', 'jenis_kelamin' => 'P', 'no_hp' => '085273645190', 'alamat' => 'Ranomeeto'],
            ['user_id' => 3, 'tempat_lahir' => 'Baruga', 'tanggal_lahir' => '2008-10-19', 'jenis_kelamin' => 'P', 'no_hp' => '085345263600', 'alamat' => 'BTN Melati'],
            ['user_id' => 4, 'tempat_lahir' => 'Kendari', 'tanggal_lahir' => '2006-05-06', 'jenis_kelamin' => 'L', 'no_hp' => '081382818881', 'alamat' => 'BTN Griya Sukses'],
        ];

        foreach ($dtdef as $dt) {
            Identitas::create([
                'user_id' => $dt['user_id'],
                'tempat_lahir' => $dt['tempat_lahir'],
                'tanggal_lahir' => $dt['tanggal_lahir'],
                'jenis_kelamin' => $dt['jenis_kelamin'],
                'no_hp' => $dt['no_hp'],
                'alamat' => $dt['alamat'],
            ]);
        }

        //identitas user
        $dtdef = [
            ['nama' => 'Beasiswa KIP'],
            ['nama' => 'Beasiswa Bank Indonesia'],
            ['nama' => 'Beasiswa Prestasi'],
            ['nama' => 'Beasiswa Tahfidz'],
            ['nama' => 'Beasiswa Lainnya'],
            ['nama' => 'Beasiswa Pemda. Kab. Bombana'],
            ['nama' => 'Beasiswa Pemda. Kab. Konawe Selatan'],
            ['nama' => 'Beasiswa Pemda. Kab. Konawe Utara'],
            ['nama' => 'Beasiswa Pemda. Kab. Kolaka Utara'],
            ['nama' => 'Beasiswa Pemda. Kab. Konawe Kepulauan'],
            ['nama' => 'Beasiswa Pemda. Kab. Wakatobi'],
        ];

        foreach ($dtdef as $dt) {
            JenisBeasiswa::create([
                'nama' => $dt['nama'],
            ]);
        }

        //role user
        $dtdef = [
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 1, 'role_id' => 2],
            ['user_id' => 2, 'role_id' => 2],
            ['user_id' => 3, 'role_id' => 2],
            ['user_id' => 4, 'role_id' => 2],
        ];
        foreach ($dtdef as $dt) {
            UserRole::create([
                'user_id' => $dt['user_id'],
                'role_id' => $dt['role_id'],
            ]);
        }

        //fakultas
        $dtdef = [
            ['nama' => 'Tarbiyah dan Ilmu Keguruan', 'singkatan' => 'FATIK', 'urut' => 1],
            ['nama' => 'Syariah', 'singkatan' => 'FAKSYAR', 'urut' => 2],
            ['nama' => 'Ushuluddin, Adab dan Dakwah', 'singkatan' => 'FUAD', 'urut' => 3],
            ['nama' => 'Ekonomi dan Bisnis Islam', 'singkatan' => 'FEBI', 'urut' => 4],
        ];
        foreach ($dtdef as $dt) {
            Fakultas::create([
                'nama' => $dt['nama'],
                'singkatan' => $dt['singkatan'],
                'urut' => $dt['urut'],
            ]);
        }

        //program studi
        $dtdef = [
            ['nama' => 'Pendidkan Agama Islam', 'singkatan' => 'PAI', 'urut' => 1, 'fakultas_id' => 1],
            ['nama' => 'Pendidkan Bahasa Arab', 'singkatan' => 'PBA', 'urut' => 2, 'fakultas_id' => 1],
            ['nama' => 'Manajemen Pendidkan Islam', 'singkatan' => 'MPI', 'urut' => 3, 'fakultas_id' => 1],
            ['nama' => 'Pendidikan Guru Madrasah Ibtidaiyah', 'singkatan' => 'PGMI', 'urut' => 4, 'fakultas_id' => 1],
            ['nama' => 'Pendidkan Anak Usia Dini', 'singkatan' => 'PIAUD', 'urut' => 5, 'fakultas_id' => 1],
            ['nama' => 'Tadris Bahasa Inggris', 'singkatan' => 'TBI', 'urut' => 6, 'fakultas_id' => 1],
            ['nama' => 'Tadris IPA', 'singkatan' => 'TIPA', 'urut' => 7, 'fakultas_id' => 1],
            ['nama' => 'Tadris Fisika', 'singkatan' => 'TFSK', 'urut' => 8, 'fakultas_id' => 1],
            ['nama' => 'Tadris Biologi', 'singkatan' => 'TBLG', 'urut' => 9, 'fakultas_id' => 1],
            ['nama' => 'Tadris Matematika', 'singkatan' => 'TMKT', 'urut' => 10, 'fakultas_id' => 1],

            ['nama' => 'Hukum Keluarga Islam', 'singkatan' => 'TFSK', 'urut' => 1, 'fakultas_id' => 2],
            ['nama' => 'Hukum Ekonomi Syariah', 'singkatan' => 'HES', 'urut' => 2, 'fakultas_id' => 2],
            ['nama' => 'Hukum Tata Negara', 'singkatan' => 'HTN', 'urut' => 3, 'fakultas_id' => 2],

            ['nama' => 'Komunikasi Penyiaran Islam', 'singkatan' => 'KPI', 'urut' => 1, 'fakultas_id' => 3],
            ['nama' => 'Bimbingan Penyuluhan Islam', 'singkatan' => 'BPI', 'urut' => 1, 'fakultas_id' => 3],
            ['nama' => 'Manajemen Dakwah', 'singkatan' => 'MD', 'urut' => 1, 'fakultas_id' => 3],
            ['nama' => 'Ilmu Al-Quran Tafsir', 'singkatan' => 'IQT', 'urut' => 1, 'fakultas_id' => 3],

            ['nama' => 'Ekonomi Syariah', 'singkatan' => 'ESY', 'urut' => 1, 'fakultas_id' => 4],
            ['nama' => 'Perbankan Syariah', 'singkatan' => 'PBS', 'urut' => 1, 'fakultas_id' => 4],
            ['nama' => 'Manajemen Bisnis Syariah', 'singkatan' => 'MBS', 'urut' => 1, 'fakultas_id' => 4],

        ];
        foreach ($dtdef as $dt) {
            ProgramStudi::create([
                'nama' => $dt['nama'],
                'singkatan' => $dt['singkatan'],
                'urut' => $dt['urut'],
                'fakultas_id' => $dt['fakultas_id'],
            ]);
        }

        //referensi pilihan
        $dtdef = [
            ['grup' => 'MCK', 'nama' => 'Tidak Ada', 'nilai' => 1],
            ['grup' => 'MCK', 'nama' => 'WC Umum', 'nilai' => 2],
            ['grup' => 'MCK', 'nama' => 'Pribadi', 'nilai' => 3],
            ['grup' => 'Sumber Biaya', 'nama' => 'Mandiri', 'nilai' => 1],
            ['grup' => 'Sumber Biaya', 'nama' => 'Orang Lain', 'nilai' => 2],
            ['grup' => 'Sumber Biaya', 'nama' => 'Keluarga', 'nilai' => 3],
            ['grup' => 'Sumber Biaya', 'nama' => 'Bapak atau Ibu', 'nilai' => 4],
            ['grup' => 'Sumber Biaya', 'nama' => 'Bapak dan Ibu', 'nilai' => 5],
            ['grup' => 'Sumber Listrik', 'nama' => 'Tidak Ada', 'nilai' => 1],
            ['grup' => 'Sumber Listrik', 'nama' => 'Genset/Solar Panel Bantuan', 'nilai' => 1],
            ['grup' => 'Sumber Listrik', 'nama' => 'PLN', 'nilai' => 2],
            ['grup' => 'Sumber Listrik', 'nama' => 'Genset/Solar Panel Pribadi', 'nilai' => 3],
            ['grup' => 'Sumber Air', 'nama' => 'Sungai/Air Terjun', 'nilai' => 1],
            ['grup' => 'Sumber Air', 'nama' => 'Sumur/Tandon Masyarakat', 'nilai' => 2],
            ['grup' => 'Sumber Air', 'nama' => 'Sumur Gali Pribadi', 'nilai' => 3],
            ['grup' => 'Sumber Air', 'nama' => 'PDAM', 'nilai' => 4],
            ['grup' => 'Sumber Air', 'nama' => 'Sumur Bor', 'nilai' => 5],
            ['grup' => 'Kepemilikan Rumah', 'nama' => 'Menumpang', 'nilai' => 1],
            ['grup' => 'Kepemilikan Rumah', 'nama' => 'Sewa/ Kontrak', 'nilai' => 2],
            ['grup' => 'Kepemilikan Rumah', 'nama' => 'Warisan', 'nilai' => 3],
            ['grup' => 'Kepemilikan Rumah', 'nama' => 'KPR', 'nilai' => 4],
            ['grup' => 'Kepemilikan Rumah', 'nama' => 'Orang Tua', 'nilai' => 5],
            ['grup' => 'Kepemilikan Rumah', 'nama' => 'Rumah Dinas', 'nilai' => 6],
            ['grup' => 'Pekerjaan', 'nama' => 'Tidak Ada', 'nilai' => 1],
            ['grup' => 'Pekerjaan', 'nama' => 'Buruh/ Nelayan/ Ojek/ Buruh Tani', 'nilai' => 2],
            ['grup' => 'Pekerjaan', 'nama' => 'Pensiunan', 'nilai' => 3],
            ['grup' => 'Pekerjaan', 'nama' => 'Karyawan Swasta', 'nilai' => 4],
            ['grup' => 'Pekerjaan', 'nama' => 'ASN PNS/ PKKK', 'nilai' => 5],
            ['grup' => 'Pekerjaan', 'nama' => 'Pejabat Daerah/ Provinsi', 'nilai' => 6],
            ['grup' => 'Pendidikan', 'nama' => 'Tidak Ada', 'nilai' => 1],
            ['grup' => 'Pendidikan', 'nama' => 'SD', 'nilai' => 2],
            ['grup' => 'Pendidikan', 'nama' => 'SMP', 'nilai' => 3],
            ['grup' => 'Pendidikan', 'nama' => 'SMA', 'nilai' => 4],
            ['grup' => 'Pendidikan', 'nama' => 'Diploma', 'nilai' => 5],
            ['grup' => 'Pendidikan', 'nama' => 'S1', 'nilai' => 6],
            ['grup' => 'Pendidikan', 'nama' => 'S2', 'nilai' => 7],
            ['grup' => 'Pendidikan', 'nama' => 'S3', 'nilai' => 8],
            ['grup' => 'Pendapatan', 'nama' => 'Tidak Ada', 'nilai' => 1],
            ['grup' => 'Pendapatan', 'nama' => '<  Rp. 1.000.000', 'nilai' => 2],
            ['grup' => 'Pendapatan', 'nama' => '>  Rp. 1.000.000 sd Rp. 3.000.000', 'nilai' => 3],
            ['grup' => 'Pendapatan', 'nama' => '>  Rp. 3.000.000 sd Rp. 6.000.000', 'nilai' => 4],
            ['grup' => 'Pendapatan', 'nama' => '>  Rp. 6.000.000 sd Rp. 9.000.000', 'nilai' => 5],
            ['grup' => 'Pendapatan', 'nama' => '>  Rp. 9.000.000', 'nilai' => 6],
            ['grup' => 'Listrik', 'nama' => 'Tidak Ada', 'nilai' => 1],
            ['grup' => 'Listrik', 'nama' => '<=  Rp. 50.000', 'nilai' => 2],
            ['grup' => 'Listrik', 'nama' => '>  Rp. 50.000 sd Rp. 150.000', 'nilai' => 3],
            ['grup' => 'Listrik', 'nama' => '>  Rp. 150.000 sd Rp. 300.000', 'nilai' => 4],
            ['grup' => 'Listrik', 'nama' => '>  Rp. 350.000 sd Rp. 450.000', 'nilai' => 5],
            ['grup' => 'Listrik', 'nama' => '>  Rp. 450.000', 'nilai' => 6],
        ];
        foreach ($dtdef as $dt) {
            ReferensiPilihan::create([
                'grup' => $dt['grup'],
                'nama' => $dt['nama'],
                'nilai' => $dt['nilai'],
            ]);
        }



        //beasiswa
        $dtdef = [
            [
                'nama' => 'Rekruitmen Baru Beasiswa KIP Angkatan Tahun 2025',
                'syarat_tahun_angkatan_mahasiswa' => "2025",
                'syarat_tahun_lulus_sma' => "2025,2024,2023",
                'tahun' => 2025,
                'daftar_mulai' => date('Y-m-1'),
                'daftar_selesai' => date('Y-m-20'),
                'verifikasi_berkas_mulai' => date('Y-m-20'),
                'verifikasi_berkas_selesai' => date('Y-m-23'),
                'survei_lapangan_mulai' => date('Y-m-23'),
                'survei_lapangan_selesai' => date('Y-m-25'),
                'wawancara_mulai' => date('Y-m-26'),
                'wawancara_selesai' => date('Y-m-26'),
                'pengumuman_verifikasi_berkas' => date('Y-m-23'),
                'pengumuman_akhir' => date('Y-m-27'),
                'ada_wawancara' => 1,
                'perlu_data_orang_tua' => 1,
                'perlu_data_rumah' => 1,
                'perlu_data_nilai_raport' => 1,
                'perlu_data_pendidikan_akhir' => 1,
                'jenis_beasiswa_id' => 1,
                'user_id' => 1,
            ],
            [
                'nama' => 'Beasiswa Prestasi IAIN Kendari',
                'syarat_tahun_angkatan_mahasiswa' => "2024,2023,2022",
                'syarat_tahun_lulus_sma' => null,
                'tahun' => 2025,
                'daftar_mulai' => date('Y-m-1'),
                'daftar_selesai' => date('Y-m-20'),
                'verifikasi_berkas_mulai' => date('Y-m-20'),
                'verifikasi_berkas_selesai' => date('Y-m-23'),
                'survei_lapangan_mulai' => date('Y-m-23'),
                'survei_lapangan_selesai' => date('Y-m-25'),
                'wawancara_mulai' => date('Y-m-26'),
                'wawancara_selesai' => date('Y-m-26'),
                'pengumuman_verifikasi_berkas' => date('Y-m-23'),
                'pengumuman_akhir' => date('Y-m-27'),
                'ada_wawancara' => 1,
                'perlu_data_orang_tua' => 1,
                'perlu_data_rumah' => 1,
                'perlu_data_nilai_raport' => 1,
                'perlu_data_pendidikan_akhir' => 1,
                'jenis_beasiswa_id' => 3,
                'user_id' => 1,
            ],
        ];
        foreach ($dtdef as $dt) {
            Beasiswa::create([
                'user_id' => $dt['user_id'],
                'nama' => $dt['nama'],
                'syarat_tahun_angkatan_mahasiswa' => $dt['syarat_tahun_angkatan_mahasiswa'],
                'syarat_tahun_lulus_sma' => $dt['syarat_tahun_lulus_sma'],
                'tahun' => $dt['tahun'],
                'daftar_mulai' => $dt['daftar_mulai'],
                'daftar_selesai' => $dt['daftar_selesai'],
                'verifikasi_berkas_mulai' => $dt['verifikasi_berkas_mulai'],
                'verifikasi_berkas_selesai' => $dt['verifikasi_berkas_selesai'],
                'survei_lapangan_mulai' => $dt['survei_lapangan_mulai'],
                'survei_lapangan_selesai' => $dt['survei_lapangan_selesai'],
                'wawancara_mulai' => $dt['wawancara_mulai'],
                'wawancara_selesai' => $dt['wawancara_selesai'],
                'pengumuman_verifikasi_berkas' => $dt['pengumuman_verifikasi_berkas'],
                'pengumuman_akhir' => $dt['pengumuman_akhir'],
                'ada_wawancara' => $dt['ada_wawancara'],
                'perlu_data_orang_tua' => $dt['perlu_data_orang_tua'],
                'perlu_data_rumah' => $dt['perlu_data_rumah'],
                'perlu_data_nilai_raport' => $dt['perlu_data_nilai_raport'],
                'perlu_data_pendidikan_akhir' => $dt['perlu_data_pendidikan_akhir'],
                'jenis_beasiswa_id' => $dt['jenis_beasiswa_id'],
            ]);
        }


        //role user
        $dtdef = [
            ['beasiswa_id' => 1, 'nama' => 'KIP/ KKS', 'jenis' => 'image', 'deskripsi' => 'Jika memiliki', 'is_wajib' => 0],
            ['beasiswa_id' => 1, 'nama' => 'Kartu Tanda Pengenal (KTP)', 'jenis' => 'image', 'deskripsi' => 'Scan KTP asli, jika hilang scan surat ketrangan asli dari pemerintah setempat', 'is_wajib' => 1],
            ['beasiswa_id' => 1, 'nama' => 'Ijazah SMA', 'jenis' => 'pdf', 'deskripsi' => 'Scan ijazah asli atau legalisir stempel asli', 'is_wajib' => 1],
            ['beasiswa_id' => 1, 'nama' => 'Raport Semester 1 sd 6', 'jenis' => 'pdf', 'deskripsi' => 'Scan asli atau legalisir stempel asli', 'is_wajib' => 1],
            ['beasiswa_id' => 1, 'nama' => 'Sertifikat Prestasi', 'jenis' => 'pdf', 'deskripsi' => 'Scan sertifikat asli (sertifikat juara 1 sd 5 baik prestasi akademik atau non akademik)', 'is_wajib' => 1],
            ['beasiswa_id' => 1, 'nama' => 'Foto Rumah', 'jenis' => 'pdf', 'deskripsi' => 'Foto bagian depan, belakang dan dapur', 'is_wajib' => 1],
            ['beasiswa_id' => 1, 'nama' => 'Surat Keterangan Tidak Mampu', 'jenis' => 'pdf', 'deskripsi' => 'Wajib bagi yang tidak memiliki KIP/KKS (wajib mengikuti format yang telah ditentukan)', 'is_wajib' => 0],
            ['beasiswa_id' => 1, 'nama' => 'Formulir Permohonan Beasiswa', 'jenis' => 'pdf', 'deskripsi' => 'Wajib asli (contoh format terlampir)', 'is_wajib' => 1],

            ['beasiswa_id' => 2, 'nama' => 'Kartu Tanda Pengenal (KTP)', 'jenis' => 'image', 'deskripsi' => 'Scan KTP asli, jika hilang scan surat ketrangan asli dari pemerintah setempat', 'is_wajib' => 1],
            ['beasiswa_id' => 2, 'nama' => 'KHS Semester Terakhir', 'jenis' => 'pdf', 'deskripsi' => 'Wajib asli', 'is_wajib' => 1],
            ['beasiswa_id' => 2, 'nama' => 'Surat Permohonan Beasiswa', 'jenis' => 'pdf', 'deskripsi' => 'Wajib asli (contoh format terlampir)', 'is_wajib' => 1],

        ];
        foreach ($dtdef as $dt) {
            Syarat::create([
                'beasiswa_id' => $dt['beasiswa_id'],
                'nama' => $dt['nama'],
                'jenis' => $dt['jenis'],
                'deskripsi' => $dt['deskripsi'],
                'is_wajib' => $dt['is_wajib'],
            ]);
        }
    }
}
