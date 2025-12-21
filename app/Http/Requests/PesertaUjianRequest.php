<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PesertaUjianRequest extends FormRequest
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
            'jadwal_ujian_id' => 'required|numeric|exists:jadwal_ujians,id',
            'pendaftar_id' => 'required|numeric|exists:pendaftars,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'jadwal_ujian_id' => 'jadwal ujian',
            'pendaftar_id' => 'peserta ujian',
        ];
    }
}
