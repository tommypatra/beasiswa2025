<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RuanganRequest extends FormRequest
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
            'gedung' => 'nullable|string',
            'kapasitas' => 'nullable|numeric',
            'lantai' => 'nullable|numeric',
            'keterangan' => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama',
            'gedung' => 'gedung',
            'kapasitas' => 'kapasitas',
            'lantai' => 'lantai',
            'keterangan' => 'keterangan',
        ];
    }
}
