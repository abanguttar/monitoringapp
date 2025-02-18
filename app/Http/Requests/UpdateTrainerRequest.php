<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainerRequest extends FormRequest
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
            'name' => 'required|unique:trainers,name,'.$this->trainer->id,
            'npwp' => 'required',
            'status_tanggungan' => 'required',
        ];
    }


    public function messages(): array{
        return [
            'name.required' => "Nama harus diisi",
            'name.unique' => "Nama sudah digunakan",
            'npwp.required' => "NPWP harus diisi",
            'status_tanggungan.required' => "Status Tanggungan harus diisi",
        ];
    }
}
