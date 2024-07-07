<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class FundWalletRequest extends FormRequest
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
        $minAmt = appSettings()->min_wallet_funding_amount;
        $maxAmt = appSettings()->max_wallet_funding_amount;
        return [
            //
            "amount" => ["required", "numeric", "min:$minAmt", "max:$maxAmt"],
            "payment_method_id" => ["required", "exists:payment_methods,id"]
        ];
    }
}
