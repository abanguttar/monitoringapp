<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePesertaRequest extends FormRequest
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
            'email' => 'required|unique:pesertas,email,' . $this->peserta->id,
            'phone' => 'required|numeric',
            // 'mitra_id' => 'required',
            // 'digital_platform_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'phone.required' => 'No Hp harus diisi',
            // 'mitra_id.required' => 'Mitra harus diisi',
            // 'digital_platform_id.required' => 'Digital Platform harus diisi',
        ];
    }
}
