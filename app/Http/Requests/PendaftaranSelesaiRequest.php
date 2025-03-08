<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranSelesaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return []; // Tidak ada inputan yang perlu divalidasi
    }

    protected function withValidator($validator)
    {
        $data = validasiPendaftaran($this->input('beasiswa_id'));

        $validator->after(function ($validator) use ($data) {
            if (!$data->pendaftaran_aktif) {
                $validator->errors()->add('pendaftaran', 'Maaf, pendaftaran sudah tertutup!');
            }

            if ($data->sudah_mendaftar) {
                $validator->errors()->add('pendaftaran', 'Anda sudah terdaftar pada beasiswa lain di tahun tersebut!');
            }
        });
    }
}
