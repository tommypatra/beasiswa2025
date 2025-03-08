<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftarRequest extends FormRequest
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
        // $id = $this->input('id');
        $data = validasiPendaftaran($this->input('beasiswa_id'));
        // dd($data->user->mahasiswa);
        if ($data->user->mahasiswa) {
            $this->merge(['mahasiswa_id' => $data->user->mahasiswa->id]);
        }

        return [
            'beasiswa_id' => 'required|numeric',
            'mahasiswa_id' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'beasiswa_id' => 'beasiswa',
            'mahasiswa_id' => 'mahasiswa',
        ];
    }

    protected function withValidator($validator)
    {
        $data = validasiPendaftaran($this->input('beasiswa_id'));

        $validator->after(function ($validator) use ($data) {
            if (!$data->pendaftaran_aktif) {
                $validator->errors()->add('pendaftaran', 'Maaf, pendaftaran sudah tertutup!');
            } elseif (!$data->nilai_raport) {
                $validator->errors()->add('pendaftaran', 'Maaf, nilai raport tidak boleh kosong!');
            } elseif (!$data->orang_tua) {
                $validator->errors()->add('pendaftaran', 'Maaf, data orang tua belum terisi!');
            } elseif (!$data->rumah) {
                $validator->errors()->add('pendaftaran', 'Maaf, wajib mengisi data rumah!');
            } elseif (!$data->pendidikan_akhir) {
                $validator->errors()->add('pendaftaran', 'Maaf, data SMA anda belum terisi!');
            } elseif ($data->user->id !== auth()->user()->id) {
                $validator->errors()->add('pendaftaran', 'Maaf, akses anda tidak diperbolehkan!');
            } elseif ($data->sudah_mendaftar) {
                $validator->errors()->add('pendaftaran', 'Anda sudah terdaftar pada beasiswa lain di tahun tersebut!');
            }
        });
    }
}
