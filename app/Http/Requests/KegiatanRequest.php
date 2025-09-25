<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KegiatanRequest extends FormRequest
{
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
            'nama' => 'required|string|max:150',
            'urut'   => 'nullable|numeric',
            'nilai_minimal'   => [
                'required',
                'numeric',
                // Batasi total digit 5, dengan 2 digit di belakang koma
                'regex:/^\d{1,3}(\.\d{1,2})?$/'
            ],
            'monitoring_id'  => [
                'required',
                'integer',
                // Pastikan ID tersebut ada di tabel monitorings kolom id
                'exists:monitorings,id'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama kegiatan',
            'urut'   => 'urut data',
            'nilai_minimal' => 'nilai minimal',
            'monitoring_id' => 'monitoring',
        ];
    }
}
