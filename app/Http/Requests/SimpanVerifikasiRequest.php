<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanVerifikasiRequest extends FormRequest
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
        $catatan_rule = $this->input('verifikasi_hasil') == 0 ? 'required' : 'nullable';
        return [
            'verifikasi_hasil' => 'required|integer',
            'verifikasi_catatan' => $catatan_rule . '|string|max:150',
            'verifikasi_skor' => 'nullable|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'verifikasi_hasil' => 'hasil',
            'verifikasi_catatan' => 'catatan',
            'verifikasi_skor' => 'skor',
        ];
    }
}
