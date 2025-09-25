<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanRequest extends FormRequest
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
            'keterangan' => 'required|string|max:150',
            'sub_kegiatan_id' => 'required|numeric',
            'penerima_id' => 'required|numeric',
            'path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ];
    }

    public function attributes(): array
    {
        return [
            'keterangan' => 'keterangan',
            'sub_kegiatan_id' => 'sub kegiatan',
            'penerima_id' => 'penerima',
            'path' => 'path file',
        ];
    }
}
