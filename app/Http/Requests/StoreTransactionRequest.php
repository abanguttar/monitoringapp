<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'peserta_id' => 'required',
            'mitra_id' => 'required',
            'digital_platform_id' => 'required',
            'kelas_id' => 'required',
            'voucher' => 'required|unique:transactions,voucher',
            'invoice' => 'required|unique:transactions,invoice',
        ];
    }

    public function messages(): array
    {
        return [
            'peserta_id.required' => 'Peserta harus diisi',
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
