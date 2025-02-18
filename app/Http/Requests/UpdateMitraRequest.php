<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMitraRequest extends FormRequest
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
        return [
            'name' => 'required',
            'address' => 'required',
            'npwp' => 'required',
            'responsible' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'Nama mitra harus diisi',
            'address' => 'Alamat mitra harus diisi',
            'npwp' => 'Nomor NPWP harus diisi',
        ];
    }
}
