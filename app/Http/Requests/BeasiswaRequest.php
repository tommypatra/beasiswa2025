<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeasiswaRequest extends FormRequest
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
        $id = $this->route('role');
        return [
            'jenis_beasiswa_id' => 'required|numeric',
            'tahun' => 'required|numeric',
            'nama' => 'required|string|max:150',
            'deskripsi' => 'nullable',
            'syarat_tahun_angkatan_mahasiswa' => 'nullable',
            'syarat_tahun_lulus_sma' => 'nullable',
            'daftar_mulai' => 'required|date|date_format:Y-m-d',
            'daftar_selesai' => 'required|date|date_format:Y-m-d',
            'verifikasi_berkas_mulai' => 'required|date|date_format:Y-m-d',
            'verifikasi_berkas_selesai' => 'required|date|date_format:Y-m-d',
            'survei_lapangan_mulai' => 'required|date|date_format:Y-m-d',
            'survei_lapangan_selesai' => 'required|date|date_format:Y-m-d',
            'wawancara_mulai' => 'required|date|date_format:Y-m-d',
            'wawancara_selesai' => 'required|date|date_format:Y-m-d',
            'pengumuman_verifikasi_berkas' => 'required|date|date_format:Y-m-d',
            'pengumuman_akhir' => 'required|date|date_format:Y-m-d',
            'ada_wawancara' => 'nullable',
            'ada_survei_lapangan' => 'nullable',
            'butuh_data_orang_tua' => 'nullable',
            'butuh_data_rumah' => 'nullable',
            'butuh_data_nilai_raport' => 'nullable',
            'butuh_data_pendidikan_akhir' => 'nullable',
            'is_aktif' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_beasiswa_id' => 'jenis beasiswa',
            'tahun' => 'tahun',
            'syarat_tahun_angkatan_mahasiswa' => 'tahun angkatan mahasiswa',
            'syarat_tahun_lulus_sma' => 'tahun lulus sma',
            'nama' => 'nama beasiswa',
            'deskripsi' => 'deskripsi',
            'daftar_mulai' => 'jadwal pendaftaran dimulai ',
            'daftar_selesai' => 'jadwal pendaftaran selesai',
            'verifikasi_berkas_mulai' => 'jadwal verifikasi dimulai',
            'verifikasi_berkas_selesai' => 'jadwal verifikasi selesai',
            'survei_lapangan_mulai' => 'jadwal survei lapangan dimulai',
            'survei_lapangan_selesai' => 'jadwal survei lapangan selesai',
            'wawancara_mulai' => 'jadwal wawancara dimulai',
            'wawancara_selesai' => 'jadwal wawancara selesai',
            'pengumuman_verifikasi_berkas' => 'pengumuman verifikasi berkas',
            'pengumuman_akhir' => 'pengumuman akhir',
            'ada_wawancara' => 'ada wawancara',
            'ada_survei_lapangan' => 'ada survei lapangan',
            'butuh_data_orang_tua' => 'kebutuhan data orang tua',
            'butuh_data_rumah' => 'kebutuhan data rumah',
            'butuh_data_nilai_raport' => 'kebutuhan data nilai raport',
            'butuh_data_pendidikan_akhir' => 'kebutuhan data pendidikan',
            'is_aktif' => 'status',
        ];
    }
}
