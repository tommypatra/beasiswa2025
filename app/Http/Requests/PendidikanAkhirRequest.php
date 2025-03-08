<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendidikanAkhirRequest extends FormRequest
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
        $id = $this->route('role');
        return [
            'nama_sekolah' => 'required|string',
            'jenis' => 'required|string',
            'jurusan' => 'required|string',
            'nisn' => 'required|numeric',
            'tahun_lulus' => 'required|numeric',
            'nilai_akhir_lulus' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_sekolah' => 'nama sekolah',
            'jenis' => 'jenis sekolah',
            'jurusan' => 'jurusan',
            'nisn' => 'nisn',
            'tahun_lulus' => 'tahun lulus',
            'nilai_akhir_lulus' => 'nilai lulus',
        ];
    }
}
