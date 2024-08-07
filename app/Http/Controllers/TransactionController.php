<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Enums\SubscriptionEnums;
use App\Enums\TransactionEnums;
use App\Interfaces\ISubscriptionPlanRepository;
use App\Models\Billings\Transaction;
use App\Services\PayGateways\StripePaymentService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //

    public $subsPlanRepo;
    public function __construct(ISubscriptionPlanRepository $subsRepo)
    {
        $this->subsPlanRepo = $subsRepo;
    }

    public function validateTransaction()
    {
    }

    public function validateWebhook(Request $request, $gateway)
    {
        switch ($gateway) {
            case "stripe":
                $payload = @file_get_contents('php://input');
                $stripeService = new StripePaymentService();
                $event = $stripeService->verifyStripeWebhook($payload);
                if ($event->type == 'checkout.session.completed') {
                    $payload = json_decode($payload, true);
                    $data = $payload["data"]["object"];
                    $transRef = $data["metadata"]["transRef"];
                    $trans = Transaction::findOrFail($transRef);
                    $payData = [
                        "reference" => $data["payment_intent"],
                        "status" => $data["payment_status"] == "paid" ? TransactionEnums::successfulStatus : TransactionEnums::failedStatus
                    ];
                    $this->processTransaction($trans, $payData);
                }
                break;
            default:
                return ApiResponse::failed("Payment method not available");
        }
    }

    private function processTransaction($trans, $payData)
    {
        if ($trans->status != TransactionEnums::pendingStatus)
            abort(200, "Transaction has been previously processed");
        $trans->update($payData);
        switch ($trans->transaction_type) {
            case SubscriptionEnums::planRenewalCode:
            case SubscriptionEnums::planPurchaseCode:
                $this->subsPlanRepo->processPlanPurchase($trans->account, $trans->id);
                break;
            default:
                abort(400, "Invalid request");
                break;
        }
        return ApiResponse::success("Transaction processed successfully", $trans);
    }
}
