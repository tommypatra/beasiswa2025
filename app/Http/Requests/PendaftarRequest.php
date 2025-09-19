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

    protected function prepareForValidation()
    {
        $data = validasiPendaftaran($this->input('beasiswa_id'));

        if ($data->user->mahasiswa) {
            $this->merge([
                'mahasiswa_id' => $data->user->mahasiswa->id,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
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
            } elseif (!$data->identitas) {
                $validator->errors()->add('pendaftaran', 'Maaf, lengkapi dulu semua data identitas anda!');
            } elseif (!$data->data_mahasiswa) {
                $validator->errors()->add('pendaftaran', 'Maaf, lengkapi dulu data mahasiswa!');
            } elseif (!$data->nilai_raport) {
                $validator->errors()->add('pendaftaran', 'Maaf, lengkapi dulu nilai raport atau upload raport anda!');
            } elseif (!$data->orang_tua) {
                $validator->errors()->add('pendaftaran', 'Maaf, lengkapi dulu data orang tua!');
            } elseif (!$data->rumah) {
                $validator->errors()->add('pendaftaran', 'Maaf, lengkapi dulu data rumah atau upload foto rumah anda!');
            } elseif (!$data->pendidikan_akhir) {
                $validator->errors()->add('pendaftaran', 'Maaf, lengkapi dulu data SMA atau upload ijazah anda!');
            } elseif (!$data->angkatan_mahasiswa) {
                $validator->errors()->add('pendaftaran', 'Maaf, syarat tahun angkatan mahasiswa tidak memenuhi!');
            } elseif (!$data->lulus_sma) {
                $validator->errors()->add('pendaftaran', 'Maaf, syarat tahun lulus SMA tidak memenuhi!');
            } elseif ($data->user->id !== auth()->user()->id) {
                $validator->errors()->add('pendaftaran', 'Maaf, akses anda tidak diperbolehkan!');
            } elseif (!$data->ukt_memenuhi) {
                $validator->errors()->add('pendaftaran', 'Maaf, nilai UKT anda tidak memenuhi untuk mendaftar di beasiswa ini!');
            } elseif ($data->sudah_mendaftar) {
                $validator->errors()->add('pendaftaran', 'Anda sudah terdaftar pada beasiswa lain di tahun tersebut!');
            }
        });
    }
}
