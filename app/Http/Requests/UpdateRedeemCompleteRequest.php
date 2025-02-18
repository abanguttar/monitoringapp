<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRedeemCompleteRequest extends FormRequest
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
            'redeem_at' => 'nullable',
            'finish_at' => 'nullable',
            'redeem_code' => 'nullable|unique:transactions,redeem_code,' . $this->transaction->id,
        ];
    }

    public function messages(): array{
        return [
            'redeem_code.unique' => 'Redeem Code sudah digunakan'
        ];
    }
}
