<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MahasiswaRequest extends FormRequest
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
        $isCreate = $this->isMethod('post');
        return [
            'nim' => 'required|string',
            'tahun_masuk' => 'required|numeric',
            'program_studi_id' => 'required|numeric',
            'sumber_biaya_id' => 'required|numeric',
            'kartu_mahasiswa' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'nim' => 'nomor induk mahasiswa',
            'tahun_masuk' => 'tahun masuk',
            'program_studi_id' => 'program studi',
            'sumber_biaya_id' => 'sumber biaya studi',
            'kartu_mahasiswa' => 'kartu mahasiswa',
        ];
    }
}
