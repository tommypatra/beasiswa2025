<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengaturanUjianRequest extends FormRequest
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
        return [
            'peserta_per_ruangan' => 'nullable|integer',
            'cetak_kartu_ujian' => 'nullable|string',
            'beasiswa_id'  => [
                'required',
                'integer',
                'exists:beasiswas,id'
            ],
            'tanggal_mulai' => 'required|date|date_format:Y-m-d',
            'tanggal_selesai' => 'required|date|date_format:Y-m-d',

        ];
    }

    public function attributes(): array
    {
        return [
            'peserta_per_ruangan' => 'jumlah peserta per ruangan',
            'cetak_kartu_ujian' => 'format cetak kartu ujian',
            'beasiswa_id' => 'beasiswa',
            'tanggal_mulai' => 'tanggal mulai',
            'tanggal_selesai' => 'tanggal selesai',
        ];
    }
}
