<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesertaRequest extends FormRequest
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
            'email' => 'required|unique:pesertas,email',
            'phone' => 'required|numeric',
            // 'mitra_id' => 'required',
            // 'kelas_id' => 'required',
            // 'digital_platform_id' => 'required',
            // 'voucher' => 'required|unique:transactions,voucher',
            // 'invoice' => 'required|unique:transactions,invoice',
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
            // 'kelas_id.required' => 'Kelas dan Jadwal harus diisi',
            // 'digital_platform_id.required' => 'Digital Platform harus diisi',
            // 'voucher.required' => 'Voucher harus diisi',
            // 'invoice.required' => 'Invoice harus diisi',
            // 'voucher.unique' => 'Voucher sudah digunakan',
            // 'invoice.unique' => 'Invoice sudah digunakan',
        ];
    }
}
