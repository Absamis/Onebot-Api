<?php

namespace App\Services;

use App\DTOs\PayGatewayData;
use App\Enums\TransactionEnums;
use App\Services\PayGateways\StripePaymentService;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function generateTransactionId()
    {
        return uniqid(mt_rand(001, 999));
    }

    public static function initiateTransaction($amount, $payMethod, $transType, $narration = null)
    {
        $transRef = TransactionService::generateTransactionId();
        $payUrl = $payRef = null;
        switch ($payMethod) {
            case "stripe":
                $rdr = $_GET["redirect_url"] ?? null;
                if (!$rdr)
                    abort(400, "redirect_url is required");
                $payData = [
                    "name" => $transType,
                    "amount" => $amount,
                    "currency" => appSettings()->currency_code
                ];
                $stripeService = new StripePaymentService();
                $resp = $stripeService->InitCheckOut($transRef, $payData, $rdr);
                $payUrl = $resp["url"];
                break;
            default:
                abort(400, "Payment method is not available");
        }

        Auth::account()->transactions()->create([
            "id" => $transRef,
            "transaction_type" => $transType,
            "amount" => $amount,
            "currency" => appSettings()->currency_code,
            "narration" => $narration,
            "payment_method" => $payMethod,
            "payment_reference" => $payRef,
            "transaction_date" => now()->toDateTime(),
            "status" => TransactionEnums::pendingStatus
        ]);
        return new PayGatewayData(
            url: $payUrl,
            reference: $transRef
        );
    }
}
