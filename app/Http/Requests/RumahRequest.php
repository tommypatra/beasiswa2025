<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RumahRequest extends FormRequest
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
            'status_id' => 'required|string',
            'luas_tanah' => 'required|numeric',
            'luas_bangunan' => 'required|numeric',
            'jumlah_orang_tinggal' => 'required|numeric',
            'mck_id' => 'required|numeric',
            'bayar_listrik_id' => 'required|numeric',
            'sumber_air_id' => 'required|numeric',
            'sumber_listrik_id' => 'required|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'status_id' => 'status kepemilikan rumah',
            'luas_tanah' => 'luas tanah',
            'luas_bangunan' => 'luas bangunan',
            'jumlah_orang_tinggal' => 'jumlah orang tinggal',
            'sumber_air_id' => 'required|numeric',
            'mck_id' => 'mck',
            'bayar_listrik_id' => 'biaya listrik bulanan',
            'sumber_air_id' => 'sumber air',
            'sumber_listrik_id' => 'sumber listrik',
        ];
    }
}
