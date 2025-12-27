<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MateriUjianRequest extends FormRequest
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
            'ujian' => 'required|string',
            'urut' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'beasiswa_id'  => 'required|numeric|exists:beasiswas,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'ujian' => 'jenis ujian',
            'beasiswa_id' => 'beasiswa',
            'urut' => 'nomor urut',
            'keterangan' => 'keterangan',
        ];
    }
}
