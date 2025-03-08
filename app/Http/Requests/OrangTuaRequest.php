<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrangTuaRequest extends FormRequest
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
            'bapak_nama' => 'required|string|max:150',
            'pekerjaan_bapak_id' => 'required|numeric',
            'pendidikan_bapak_id' => 'required|numeric',
            'pendapatan_bapak_id' => 'required|numeric',
            'status_hidup_bapak_kandung' => 'required|numeric',
            'ibu_nama' => 'required|string|max:150',
            'pekerjaan_ibu_id' => 'required|numeric',
            'pendidikan_ibu_id' => 'required|numeric',
            'pendapatan_ibu_id' => 'required|numeric',
            'status_hidup_ibu_kandung' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'bapak_nama' => 'nama bapak',
            'pekerjaan_bapak_id' => 'pekerjaan bapak',
            'pendidikan_bapak_id' => 'pendidikan akhir bapak',
            'pendapatan_bapak_id' => 'pendapatan bapak',
            'status_hidup_bapak_kandung' => 'status hidup bapak kandung',
            'ibu_nama' => 'nama ibu',
            'pekerjaan_ibu_id' => 'pekerjaan ibu',
            'pendidikan_ibu_id' => 'pendidikan akhir ibu',
            'pendapatan_ibu_id' => 'pendapatan ibu',
            'status_hidup_ibu_kandung' => 'status hidup ibu',
        ];
    }
}
