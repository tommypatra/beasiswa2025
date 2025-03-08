<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleRequest extends FormRequest
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
            'user_id' => 'required|numeric',
            'role_id' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'user',
            'role_id' => 'role akses',
        ];
    }
}
