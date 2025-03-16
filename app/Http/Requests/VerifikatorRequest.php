<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikatorRequest extends FormRequest
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
        $id = $this->route('verifikator');
        return [
            'user_id' => 'required|numeric',
            'beasiswa_id' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'akun verifikator',
            'beasiswa_id' => 'pilihan beasiswa',
        ];
    }
}
