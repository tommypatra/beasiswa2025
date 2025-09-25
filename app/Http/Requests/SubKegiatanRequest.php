<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubKegiatanRequest extends FormRequest
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
            'nama' => 'required|string|max:100',
            'bukti' => 'required|string|max:100',
            'urut'   => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'tingkat_id'  => [
                'nullable',
                'integer',
                'exists:referensi_pilihans,id'
            ],
            'pjp_id'  => [
                'nullable',
                'integer',
                'exists:referensi_pilihans,id'
            ],

            'nilai'   => [
                'required',
                'numeric',
                // Batasi total digit 5, dengan 2 digit di belakang koma
                'regex:/^\d{1,3}(\.\d{1,2})?$/'
            ],
            'kegiatan_id'  => [
                'required',
                'integer',
                'exists:kegiatans,id'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama kegiatan',
            'bukti' => 'bukti',
            'keterangan' => 'keterangan',
            'tingkat_id' => 'tingkat',
            'pjp_id' => 'parisipasi/ jabatan/ prestasi',
            'kegiatan_id' => 'kegiatan',
            'urut'   => 'urut data',
            'nilai' => 'nilai',
        ];
    }
}
