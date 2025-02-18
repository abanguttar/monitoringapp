<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportPesertaRequest extends FormRequest
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
            'mitra_id' => 'required',
            'kelas_id' => 'required',
            'digital_platform_id' => 'required',
            'file' => 'required|mimes:xls,xlsx',
        ];
    }

    public function messages(): array
    {
        return [
            'mitra_id.required' => 'Mitra harus diisi',
            'kelas_id.required' => 'Kelas dan Jadwal harus diisi',
            'digital_platform_id.required' => 'Digital Platform harus diisi',
            'file.required' => 'File harus diisi',
            'file.mimes' => 'File harus berupa excel yang valid',
        ];
    }
}
