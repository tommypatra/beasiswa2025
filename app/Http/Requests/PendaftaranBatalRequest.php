<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranBatalRequest extends FormRequest
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
            'alasan' => 'required|string|max:190',
            'beasiswa_id' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'alasan' => 'alasan batal pedaftaran',
            'beasiswa_id' => 'beasiswa',
        ];
    }

    protected function withValidator($validator)
    {
        $data = validasiPendaftaran($this->input('beasiswa_id'));

        $validator->after(function ($validator) use ($data) {
            if ($data->user->id !== auth()->user()->id) {
                $validator->errors()->add('pendaftaran', 'Maaf, akses anda tidak diperbolehkan!');
            } elseif ($data->finalisasi) {
                $validator->errors()->add('pendaftaran', 'Maaf, sudah terfinalisasi! silahkan refresh halaman ini');
            }
        });
    }
}
