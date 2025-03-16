<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanValidasiFinalRequest extends FormRequest
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
            'hasil' => 'required|numeric|in:0,1', // Pastikan hanya 0 atau 1 yang diterima
            'catatan' => 'required_if:hasil,0|string|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'catatan' => 'catatan',
            'hasil' => 'hasil verifikasi',
        ];
    }
}
