<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikatorPendaftarRequest extends FormRequest
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
            'verifikator_id' => 'required|numeric',
            'pendaftar_id' => 'required|array', // Pastikan pendaftar_id adalah array
            'pendaftar_id.*' => 'required|numeric', // Pastikan setiap elemen dalam array adalah angka
        ];
    }

    public function attributes(): array
    {
        return [
            'verifikator_id' => 'verifikator',
            'pendaftar_id' => 'pendaftar',
            'pendaftar_id.*' => 'pendaftar',
        ];
    }
}
