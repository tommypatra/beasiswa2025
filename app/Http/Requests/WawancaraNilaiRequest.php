<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WawancaraNilaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('verifikator');
        return [
            'pewawancara_id' => 'required|numeric',
            'soal_wawancara_id' => 'required|numeric',
            'pendaftar_id' => 'required|numeric',
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'pewawancara_id' => 'pewawancara',
            'soal_wawancara_id' => 'soal',
            'pendaftar_id' => 'pendaftar',
            'nilai' => 'nilai',
            'catatan' => 'catatan',
        ];
    }
}
