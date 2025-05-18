<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonitoringRequest extends FormRequest
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
        // $id = $this->route('mahasiswa');
        $isCreate = !$this->input('id');;
        return [
            'nama' => 'required|string',
            'tanggal_sk' => 'required|date_format:Y-m-d',
            'nomor_sk' => 'required|string',
            'dokumen' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal_sk' => 'tanggal sk',
            'nomor_sk' => 'nomor sk',
            'name' => 'nama monitoring',
            'dokumen' => 'file dokumen pedoman monitoring',
        ];
    }
}
