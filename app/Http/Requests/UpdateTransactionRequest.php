<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'digital_platform_id' => 'required',
            'kelas_id' => 'required',
            'voucher' => 'required|unique:transactions,voucher,' . $this->transaction->id,
            'invoice' => 'required|unique:transactions,invoice,' . $this->transaction->id,
        ];
    }

    public function messages(): array
    {
        return [
            'mitra_id.required' => 'Mitra harus diisi',
            'digital_platform_id.required' => 'Digital Platform harus diisi',
            'kelas_id.required' => 'Kelas dan Jadwal harus diisi',
            'voucher.required' => 'Voucher harus diisi',
            'invoice.required' => 'Invoice harus diisi',
            'voucher.unique' => 'Voucher sudah digunakan',
            'invoice.unique' => 'Invoice sudah digunakan',
        ];
    }
}
