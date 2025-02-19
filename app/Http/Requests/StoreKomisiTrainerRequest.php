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

        // if ($this->type === 'minimum') {
        //     $rules =  [
        //         'trainer' => 'required',
        //         'komisi' => 'required|numeric',
        //         'type' => 'required',

        //     ];
        // } else {
        $day = $this->day;

        $rules =  [
            "trainer_1.$day" => "required",
            "komisi_1.$day" => "required|numeric",
            "trainer_2.$day" => "nullable",
            "komisi_2.$day" => "nullable|numeric",
            "type" => "required",
            "day" => 'required',

        ];
        $rules["trainer_2.$day"] = "nullable";
        $rules["komisi_2.$day"] = "nullable|numeric";
        if ($this->komisi_2[$day]) {
            $rules["trainer_2.$day"] = "required";
        }
        if ($this->trainer_2[$day]) {
            $rules["komisi_2.$day"] = "required|numeric";
        }
        // }
        return $rules;
    }


    public function messages(): array
    {
        $day = $this->day ?? 0;
        return [
            'trainer.required' => 'Trainer harus diisi',
            'komisi.required' => 'Komisi harus diisi',
            'komisi.numeric' => 'Komisi harus berupa angka',
            "trainer_1.$day.required" => "Trainer harus diisi",
            "komisi_1.$day.required" => "Komisi harus diisi",
            "komisi_1.$day.numeric" => "Komisi harus berupa angka",
            "trainer_2.$day.required" => "Trainer harus diisi",
            "komisi_2.$day.required" => "Komisi harus diisi",
            "komisi_2.$day.numeric" => 'Komisi harus berupa angka',
        ];
    }
}
