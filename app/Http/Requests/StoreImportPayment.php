<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportPayment extends FormRequest
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
            'tipe' => 'required',
            'file' => 'required|mimes:xls,xlsx',
        ];
    }
    public function messages(): array
    {
        return [
            'tipe.required' => 'Tipe harus diisi',
            'file.required' => 'File harus diisi',
            'file.mimes' => 'File harus berupa excel yang valid',
        ];
    }
}
