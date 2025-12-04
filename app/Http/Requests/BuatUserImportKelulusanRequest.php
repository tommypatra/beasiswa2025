<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuatUserImportKelulusanRequest extends FormRequest
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
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:150'
            ],

            'fakultas'      => 'nullable|string|max:150',
            'idprodi'       => 'nullable|string|max:30',
            'program_studi_id'  => 'required|numeric',
            'jenis_kelamin' => [
                'nullable',
                'in:L,P'
            ],

            'name' => 'required|string|max:150',

            'nim' => [
                'required',
                'string',
                'max:20',
                'unique:mahasiswas,nim'
            ],

            'no_hp' => [
                'nullable',
                'regex:/^08[0-9]{8,13}$/'
            ],
            'prodi'         => 'nullable|string|max:150',
            'tahun_masuk'   => 'nullable|string|max:5',
            'tanggal_lahir' => [
                'nullable',
                'date_format:Y-m-d'
            ],
            'tempat_lahir' => 'nullable|string|max:50',
        ];
    }
}
