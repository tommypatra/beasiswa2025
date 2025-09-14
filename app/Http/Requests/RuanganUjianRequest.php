<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RuanganUjianRequest extends FormRequest
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
            'urut' => 'required|integer',
            'jumlah_peserta' => 'required|integer',
            'beasiswa_id'  => [
                'required',
                'integer',
                'exists:beasiswas,id'
            ],
            'ruangan_id'  => [
                'required',
                'integer',
                'exists:ruangans,id'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'urut' => 'urut ruangan',
            'jumlah_peserta' => 'jumlah peserta',
            'beasiswa_id' => 'beasiswa',
            'ruangan_id' => 'ruangan dipakai',
        ];
    }
}
