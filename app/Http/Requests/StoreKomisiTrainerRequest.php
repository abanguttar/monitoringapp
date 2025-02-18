<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKomisiTrainerRequest extends FormRequest
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

        if ($this->type === 'minimum') {
            $rules =  [
                'trainer' => 'required',
                'komisi' => 'required|numeric',
                'type' => 'required',

            ];
        } else {

            $rules =  [
                'trainer_1' => 'required',
                'komisi_1' => 'required|numeric',
                'trainer_2' => 'nullable',
                'komisi_2' => 'nullable|numeric',
                'type' => 'required',
                'day' => 'required',

            ];
        }
        return $rules;
    }


    public function messages(): array
    {
        return [
            'trainer.required' => 'Trainer harus diisi',
            'komisi.required' => 'Komisi harus diisi',
            'komisi.numeric' => 'Komisi harus berupa angka',
            'trainer_1.required' => 'Trainer harus diisi',
            'komisi_1.required' => 'Komisi harus diisi',
            'komisi_1.numeric' => 'Komisi harus berupa angka',
            'trainer_2.required' => 'Trainer harus diisi',
            'komisi_2.required' => 'Komisi harus diisi',
            'komisi_2.numeric' => 'Komisi harus berupa angka',
        ];
    }
}
