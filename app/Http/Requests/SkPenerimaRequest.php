<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkPenerimaRequest extends FormRequest
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
        $id = $this->route('role');
        return [
            'nama' => 'required|string|max:150',
            'nomor_sk' => 'required|string|max:150',
            'ttd_jabatan' => 'nullable|string|max:150',
            'ttd_nama' => 'nullable|string|max:150',
            'tanggal_sk' => 'required|date_format:Y-m-d',
            'monitoring_id'  => [
                'nullable',
                'integer',
                'exists:monitorings,id'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama',
            'nomor_sk' => 'nomor SK',
            'ttd_jabatan' => 'jabatan',
            'ttd_nama' => 'nama pejabat',
            'tanggal_sk' => 'tanggal SK',
            'monitoring_id' => 'monitoring',
        ];
    }
}
