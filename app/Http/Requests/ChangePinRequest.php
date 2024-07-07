<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePinRequest extends FormRequest
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
            "token" => ["sometimes", "required"],
            "current_pin" => ["sometimes", "required", "numeric", "digits:4"],
            "new_pin" => ["required", "numeric", "digits:4", "different:current_pin"],
            "confirm_pin" => ["required", "same:new_pin"]
        ];
    }
}
