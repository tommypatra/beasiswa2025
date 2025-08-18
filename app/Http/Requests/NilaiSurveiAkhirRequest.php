<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NilaiSurveiAkhirRequest extends FormRequest
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
            // 'total_skor' => [
            //     'required',
            //     'numeric',
            //     'regex:/^\d{1,3}(\.\d{1,2})?$/', // maks 3 digit sebelum koma, maks 2 digit setelah
            //     'max:100' // nilai maksimal
            // ],
            'hasil' => 'required|numeric|in:0,1,2,3,4', // Pastikan hanya 0 atau 1 yang diterima
            'catatan' => 'required_if:hasil,0|string|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            // 'total_skor' => 'skor',
            'catatan' => 'catatan',
            'hasil' => 'hasil verifikasi',
        ];
    }
}
