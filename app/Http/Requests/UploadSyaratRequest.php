<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadSyaratRequest extends FormRequest
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
        // $id = $this->route('mahasiswa');
        $isCreate = $this->isMethod('post');
        return [
            'syarat_id' => 'required|numeric',
            'pendaftar_id' => 'required|numeric',
            'dokumen' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:pdf,jpg,png,jpeg|max:4048',
        ];
    }

    public function attributes(): array
    {
        return [
            'syarat_id' => 'syarat beasiswa',
            'pendaftar_id' => 'pendaftar',
            'dokumen' => 'dokumen',
        ];
    }

    protected function withValidator($validator)
    {
        $data = validasiPendaftaran($this->input('beasiswa_id'));

        $validator->after(function ($validator) use ($data) {
            if (!$data->pendaftaran_aktif) {
                $validator->errors()->add('pendaftaran', 'Maaf, pendaftaran sudah tertutup!');
            }
        });
    }
}
