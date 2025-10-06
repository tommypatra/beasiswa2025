<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class JadwalUjianRequest extends FormRequest
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
        $id = $this->input('id');
        return [
            'tanggal' => [
                'required',
                'date_format:Y-m-d',
                Rule::unique('jadwal_ujians', 'tanggal')
                    ->where(function ($query) {
                        return $query
                            ->where('beasiswa_id', $this->beasiswa_id)
                            ->where('ruangan_ujian_id', $this->ruangan_ujian_id)
                            ->where('sesi_ujian_id', $this->sesi_ujian_id);
                    })
                    ->ignore($id),
            ],
            'sesi' => [
                'required',
                'numeric',
                Rule::unique('jadwal_ujians', 'sesi')
                    ->where(function ($query) {
                        return $query->where('beasiswa_id', $this->beasiswa_id);
                    })
                    ->ignore($id),
            ],
            'sesi_ujian_id' => [
                'required',
                'numeric',
                'exists:sesi_ujians,id',
            ],
            'beasiswa_id' => [
                'required',
                'numeric',
                'exists:beasiswas,id',
            ],
            'ruangan_ujian_id' => [
                'required',
                'numeric',
                'exists:ruangan_ujians,id',
            ],


        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal' => 'tanggal ujian',
            'sesi' => 'sesi ujian',
            'sesi_ujian_id' => 'sesi',
            'beasiswa_id' => 'beasiswa',
            'ruangan_ujian_id' => 'ruangan ujian',
        ];
    }
}
