<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest
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
            'jadwal_name' => 'required',
            'jam' => 'nullable',
            'date' => 'nullable',
            'price' => 'required|numeric',
            'is_prakerja' => 'required',
            'metode' => 'required',
            'day' => 'required|numeric',
            'trainer_id' => 'nullable',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Nama Kelas harus diisi',
            'jadwal_name.required' => 'Nama Jadwal harus diisi',
            'price.required' => 'Harga Kelas harus diisi',
            'is_prakerja.required' => 'Tipe Kelas harus diisi',
            'metode.required' => 'Jenis Kelas harus diisi',
            'price.numeric' => 'Harga Kelas harus berupa angka',
            'day.required' => 'Jumlah Hari harus diisi',
            'day.numeric' => 'Jumlah Hari harus berupa angka',
        ];
    }
}
