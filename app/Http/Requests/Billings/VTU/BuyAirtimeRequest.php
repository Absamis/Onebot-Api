<?php

namespace App\Http\Requests\Billings\VTU;

use Illuminate\Foundation\Http\FormRequest;

class BuyAirtimeRequest extends FormRequest
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
            "provider_id" => ["required", "exists:service_providers,id"],
            "amount" => ["required", "numeric", "min:50"],
            "phone_number" => ["required", "max:15"]
        ];
    }
}
