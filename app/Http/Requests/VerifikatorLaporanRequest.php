<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikatorLaporanRequest extends FormRequest
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
        // $id = $this->route('role');
        return [
            'user_id'  => [
                'required',
                'integer',
                'exists:users,id'
            ],
            'sk_penerima_id'  => [
                'required',
                'integer',
                'exists:sk_penerimas,id'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'verifikator',
            'sk_penerima_id' => 'SK penerima beasiswa',
        ];
    }
}
