<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramStudiRequest extends FormRequest
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
            'singkatan' => 'required|string|max:25',
            'urut' => 'required|numeric',
            'fakultas_id' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama',
            'singkatan' => 'singkatan',
            'urut' => 'urut',
            'fakultas_id' => 'fakultas',
        ];
    }
}
