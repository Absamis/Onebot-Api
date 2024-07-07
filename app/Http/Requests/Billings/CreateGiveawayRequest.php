<?php

namespace App\Http\Requests\Billings;

use Illuminate\Foundation\Http\FormRequest;

class CreateGiveawayRequest extends FormRequest
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
            //
            "bill_types" => ["required", "array"],
            "service_ids" => ["required", "array"],
            "prices" => ["required", "array"],
            "prices.*" => ["required", "numeric", "min:50"],
            "quantities" => ["required", "array"],
            "quantities.*" => ["required", "numeric", "min:1"]
        ];
    }
}
