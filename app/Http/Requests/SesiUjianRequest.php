<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SesiUjianRequest extends FormRequest
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
            'sesi' => 'required|integer',
            'beasiswa_id'  => [
                'required',
                'integer',
                'exists:beasiswas,id'
            ],
            'jam_mulai'   => 'required|date_format:H:i:s',
            'jam_selesai' => 'required|date_format:H:i:s',
        ];
    }

    public function attributes(): array
    {
        return [
            'sesi' => 'sesi ujian',
            'beasiswa_id' => 'beasiswa',
            'jam_mulai' => 'jam mulai',
            'jam_selesai' => 'jam selesai',
        ];
    }
}
