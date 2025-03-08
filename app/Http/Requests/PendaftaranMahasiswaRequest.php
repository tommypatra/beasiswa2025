<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranMahasiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //identitas
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'jenis_kelamin' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'name' => 'required|string',
            'wilayah_desa_id' => 'required|numeric',
            'foto' => 'required|file|mimes:jpg,png,jpeg|max:750',

            //sekolah
            'nama_sekolah' => 'required|string',
            'jenis' => 'required|string',
            'jurusan' => 'required|string',
            'nisn' => 'required|numeric',
            'tahun_lulus' => 'required|numeric',
            'nilai_akhir_lulus' => 'required|numeric',

            //mahasiswa
            'nim' => 'required|string',
            'tahun_masuk' => 'required|numeric',
            'program_studi_id' => 'required|numeric',
            'sumber_biaya_id' => 'required|numeric',
            'kartu_mahasiswa' => 'required|file|mimes:jpg,png,jpeg|max:750',

            //orang tua
            'bapak_nama' => 'required|string|max:150',
            'pekerjaan_bapak_id' => 'required|numeric',
            'pendidikan_bapak_id' => 'required|numeric',
            'pendapatan_bapak_id' => 'required|numeric',
            'status_hidup_bapak_kandung' => 'required|numeric',
            'ibu_nama' => 'required|string|max:150',
            'pekerjaan_ibu_id' => 'required|numeric',
            'pendidikan_ibu_id' => 'required|numeric',
            'pendapatan_ibu_id' => 'required|numeric',
            'status_hidup_ibu_kandung' => 'required|numeric',

            //rumah
            'status_id' => 'required|string',
            'luas_tanah' => 'required|numeric',
            'luas_bangunan' => 'required|numeric',
            'jumlah_orang_tinggal' => 'required|numeric',
            'mck_id' => 'required|numeric',
            'bayar_listrik_id' => 'required|numeric',
            'sumber_air_id' => 'required|numeric',
            'sumber_listrik_id' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            //identitas
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'wilayah_desa_id' => 'desa',
            'no_hp' => 'nomor hp',
            'alamat' => 'alamat',
            'name' => 'nama pengguna',
            'foto' => 'foto',

            //sekolah
            'nama_sekolah' => 'nama sekolah',
            'jenis' => 'jenis sekolah',
            'jurusan' => 'jurusan',
            'nisn' => 'nisn',
            'tahun_lulus' => 'tahun lulus',
            'nilai_akhir_lulus' => 'nilai lulus',

            //mahasiswa
            'nim' => 'nomor induk mahasiswa',
            'tahun_masuk' => 'tahun masuk',
            'program_studi_id' => 'program studi',
            'sumber_biaya_id' => 'sumber biaya studi',
            'kartu_mahasiswa' => 'kartu mahasiswa',

            //orang tua
            'bapak_nama' => 'nama bapak',
            'pekerjaan_bapak_id' => 'pekerjaan bapak',
            'pendidikan_bapak_id' => 'pendidikan akhir bapak',
            'pendapatan_bapak_id' => 'pendapatan bapak',
            'status_hidup_bapak_kandung' => 'status hidup bapak kandung',
            'ibu_nama' => 'nama ibu',
            'pekerjaan_ibu_id' => 'pekerjaan ibu',
            'pendidikan_ibu_id' => 'pendidikan akhir ibu',
            'pendapatan_ibu_id' => 'pendapatan ibu',
            'status_hidup_ibu_kandung' => 'status hidup ibu',

            //rumah
            'status_id' => 'status kepemilikan rumah',
            'luas_tanah' => 'luas tanah',
            'luas_bangunan' => 'luas bangunan',
            'jumlah_orang_tinggal' => 'jumlah orang tinggal',
            'sumber_air_id' => 'required|numeric',
            'mck_id' => 'mck',
            'bayar_listrik_id' => 'biaya listrik bulanan',
            'sumber_air_id' => 'sumber air',
            'sumber_listrik_id' => 'sumber listrik',
        ];
    }
}
