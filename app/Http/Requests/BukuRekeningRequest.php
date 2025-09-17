<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BukuRekeningRequest extends FormRequest
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
            'bank' => 'required|string',
            'tanggal_pembuatan' => 'required|date_format:Y-m-d',
            'nama_pemilik' => 'required|string',
            'nomor' => 'required|string',
            'is_aktif' => 'nullable|integer',
            'foto_buku' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'bank' => 'nama bank',
            'tanggal_pembuatan' => 'tanggal pembuatan',
            'nama_pemilik' => 'nama pemilik rekening',
            'nomor' => 'nomor rekening',
            'foto_buku' => 'foto buku rekening',
            'is_aktif' => 'status aktif',

        ];
    }
}
