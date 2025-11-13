<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVerifikasiRequest extends FormRequest
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
            'verifikasi_hasil' => 'required|numeric',
            'verifikasi_skor' => 'required|numeric',
            'verifikasi_catatan' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'verifikasi_hasil' => 'hasil',
            'verifikasi_skor' => 'skor',
            'verifikasi_catatan' => 'catatan',
        ];
    }
}
