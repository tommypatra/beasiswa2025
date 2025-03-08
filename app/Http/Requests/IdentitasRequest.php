<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdentitasRequest extends FormRequest
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
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'jenis_kelamin' => 'required|string',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'name' => 'required|string',
            'wilayah_desa_id' => 'required|numeric',
            'foto' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'wilayah_desa_id' => 'desa',
            'no_hp' => 'nomor hp',
            'alamat' => 'alamat',
            'name' => 'nama pengguna',
            'foto' => 'foto',
        ];
    }
}
