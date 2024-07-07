<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignupWithEmailRequest extends FormRequest
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
            "name" => ["required", "regex:/^[a-zA-Z]{3,}\s[a-zA-Z]{3,}$/", "max:100"],
            "email" => ["required", "email", "max:150", "unique:users,email"],
            "phone_number" => ["nullable", "max:20"],
            "referral_tag" => ["nullable", "exists:users,user_tag"],
            "password" => ["required", Password::min(8)->letters()->numbers(), "max:20"],
            "confirm_password" => ["required", "same:password"],
        ];
    }

    public function messages()
    {
        return [
            "referral_tag.exists" => "Invalid referral tag. leave it empty if you have no referrer"
        ];
    }
}
