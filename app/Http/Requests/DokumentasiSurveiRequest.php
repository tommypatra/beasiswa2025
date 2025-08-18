<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DokumentasiSurveiRequest extends FormRequest
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
            'surveyor_id'  => [
                'required',
                'integer',
                'exists:surveyors,id'
            ],
            'pendaftar_id'  => [
                'required',
                'integer',
                'exists:pendaftars,id'
            ],
            'path' => 'required|file|mimes:jpg,png,jpeg|max:4048',
        ];
    }

    public function attributes(): array
    {
        return [
            'surveyor_id' => 'surveyor',
            'pendaftar_id' => 'pendaftar',
            'path' => 'dokumentasi survei',
        ];
    }
}
