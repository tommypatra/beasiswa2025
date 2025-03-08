<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyaratRequest extends FormRequest
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
            'nama' => 'required|string|max:150',
            'jenis' => 'required|string',
            'deskripsi' => 'nullable',
            'is_wajib' => 'required|numeric',
            'is_aktif' => 'required|numeric',
            'beasiswa_id' => 'required|numeric',
            'contoh' => 'nullable|file|max:2048',

        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama syarat',
            'jenis' => 'jenis dokumen',
            'deskripsi' => 'deskripsi',
            'is_wajib' => 'status wajib',
            'is_aktif' => 'status aktif',
            'beasiswa_id' => 'pilihan beasiswa',
            'contoh' => 'contoh syarat',
        ];
    }
}
