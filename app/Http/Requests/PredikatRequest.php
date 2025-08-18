<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PredikatRequest extends FormRequest
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
        // $id = $this->route('mahasiswa');
        $isCreate = !$this->input('id');;
        return [
            'predikat' => 'required|string',
            'monitoring_id'  => [
                'nullable',
                'integer',
                'exists:monitorings,id'
            ],

            'nilai_minimal'   => [
                'required',
                'numeric',
                // Batasi total digit 5, dengan 2 digit di belakang koma
                'regex:/^\d{1,3}(\.\d{1,2})?$/'
            ],
            'nilai_maksimal'   => [
                'required',
                'numeric',
                // Batasi total digit 5, dengan 2 digit di belakang koma
                'regex:/^\d{1,3}(\.\d{1,2})?$/'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'predikat' => 'predikat',
            'nilai_maksimal' => 'nilai maksimal',
            'nilai_minimal' => 'nilai minimal',
            'monitoring_id' => 'pedoman monitoring',
        ];
    }
}
