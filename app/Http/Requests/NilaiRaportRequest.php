<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NilaiRaportRequest extends FormRequest
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
            'smt_1_nilai' => 'required|numeric',
            'smt_1_peringkat' => 'nullable|numeric',
            'smt_2_nilai' => 'required|numeric',
            'smt_2_peringkat' => 'nullable|numeric',
            'smt_3_nilai' => 'required|numeric',
            'smt_3_peringkat' => 'nullable|numeric',
            'smt_4_nilai' => 'required|numeric',
            'smt_4_peringkat' => 'nullable|numeric',
            'smt_5_nilai' => 'required|numeric',
            'smt_5_peringkat' => 'nullable|numeric',
            'smt_6_nilai' => 'required|numeric',
            'smt_6_peringkat' => 'nullable|numeric',
            'foto_raport_smt_1' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:pdf|max:4096',
            'foto_raport_smt_2' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:pdf|max:4096',
            'foto_raport_smt_3' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:pdf|max:4096',
            'foto_raport_smt_4' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:pdf|max:4096',
            'foto_raport_smt_5' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:pdf|max:4096',
            'foto_raport_smt_6' => ($isCreate ? 'required' : 'nullable') . '|file|mimes:pdf|max:4096',

        ];
    }

    public function attributes(): array
    {
        return [
            'smt_1_nilai' => 'nilai semester 1',
            'smt_1_peringkat' => 'peringkat semester 1',
            'smt_2_nilai' => 'nilai semester 2',
            'smt_2_peringkat' => 'peringkat semester 2',
            'smt_3_nilai' => 'nilai semester 3',
            'smt_3_peringkat' => 'peringkat semester 3',
            'smt_4_nilai' => 'nilai semester 4',
            'smt_4_peringkat' => 'peringkat semester 4',
            'smt_5_nilai' => 'nilai semester 5',
            'smt_5_peringkat' => 'peringkat semester 5',
            'smt_6_nilai' => 'nilai semester 6',
            'smt_6_peringkat' => 'peringkat semester 6',

            'foto_raport_smt_1' => 'file pdf raport semester 6',
            'foto_raport_smt_2' => 'file pdf raport semester 2',
            'foto_raport_smt_3' => 'file pdf raport semester 3',
            'foto_raport_smt_4' => 'file pdf raport semester 4',
            'foto_raport_smt_5' => 'file pdf raport semester 5',
            'foto_raport_smt_6' => 'file pdf raport semester 6',

        ];
    }
}
