<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveiLapanganRequest extends FormRequest
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
            'verifikasi_lapangan_skor' => [
                'required',
                'numeric',
                'regex:/^\d{1,3}(\.\d{1,2})?$/', // maks 3 digit sebelum koma, maks 2 digit setelah
                'max:100' // nilai maksimal
            ],
            'verifikasi_lapangan_hasil' => 'required|numeric|in:0,1,2,3,4', // Pastikan hanya 0 atau 1 yang diterima
            'verifikasi_lapangan_catatan' => 'required_if:verifikasi_lapangan_hasil,0|string|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'verifikasi_lapangan_skor' => 'skor',
            'verifikasi_lapangan_hasil' => 'hasil verifikasi',
            'verifikasi_lapangan_catatan' => 'catatan',
        ];
    }
}
