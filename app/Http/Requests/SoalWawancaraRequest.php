<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoalWawancaraRequest extends FormRequest
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
        $id = $this->route('soal-wawancara');
        return [
            'soal' => 'required|string',
            'nomor' => 'required|numeric',
            'beasiswa_id' => 'required|numeric',
            'bobot_nilai' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'soal' => 'soal',
            'nomor' => 'nomor soal',
            'beasiswa_id' => 'beasiswa',
            'bobot_nilai' => 'bobot nilai',
        ];
    }
}
