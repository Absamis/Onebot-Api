<?php

namespace App\Http\Requests\Billings\VTU;

use Illuminate\Foundation\Http\FormRequest;

class BuyDataRequest extends FormRequest
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
            "plan_id" => ["required", "exists:data_bundle_plans,id"],
            "phone_number" => ["required", "max_digits:15", "numeric"]
        ];
    }
}
