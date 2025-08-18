<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenerimaRequest extends FormRequest
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
            'keterangan' => 'nullable|string',
            'sk_penerima_id'  => [
                'required',
                'integer',
                'exists:sk_penerimas,id'
            ],
            'user_id'  => [
                'required',
                'integer',
                'exists:users,id'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'keterangan' => 'keterangan',
            'user_id' => 'user',
            'sk_penerima_id' => 'sk penerima',
        ];
    }
}
