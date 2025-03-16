<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanValidasiSyaratRequest extends FormRequest
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
            'verifikasi_berkas_hasil' => 'required|numeric|in:0,1', // Pastikan hanya 0 atau 1 yang diterima
            'verifikasi_berkas_catatan' => 'required_if:verifikasi_berkas_hasil,0|string|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'verifikasi_berkas_catatan' => 'catatan',
            'verifikasi_berkas_hasil' => 'hasil verifikasi',
        ];
    }
}
