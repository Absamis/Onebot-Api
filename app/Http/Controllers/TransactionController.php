<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Enums\SubscriptionEnums;
use App\Enums\TransactionEnums;
use App\Interfaces\ISubscriptionPlanRepository;
use App\Models\Account;
use App\Models\Billings\Transaction;
use App\Services\PayGateways\StripePaymentService;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //

    public $subsPlanRepo;
    public function __construct(ISubscriptionPlanRepository $subsRepo)
    {
        $this->subsPlanRepo = $subsRepo;
    }

    public function validateTransaction(Account $account, Transaction $trans)
    {
        switch ($trans->payment_method) {
            case "stripe":
                $stripeService = new StripePaymentService();
                $data = $stripeService->getTransaction($trans->payment_reference);
                $id = $data["id"] ?? null;
                if (!$id)
                    return ApiResponse::failed("Failed to validate transaction. Try again");
                $payData = [
                    "payment_reference" => $data["id"],
                    "status" => $data["payment_status"] == "paid" ? TransactionEnums::successfulStatus : TransactionEnums::failedStatus
                ];
                return $this->processTransaction($trans, $payData);
                break;
            default:
                ApiResponse::failed("Payment gateway not available");
                break;
        }
    }

    public function validateWebhook(Request $request, $gateway)
    {
        try {
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
                            "payment_reference" => $data["id"],
                            "status" => $data["payment_status"] == "paid" ? TransactionEnums::successfulStatus : TransactionEnums::failedStatus
                        ];
                        return $this->processTransaction($trans, $payData);
                    }
                    break;
                default:
                    return ApiResponse::failed("Payment method not available");
            }
        } catch (Exception $ex) {
            return ApiResponse::failed($ex->getMessage());
        }
    }

    private function processTransaction($trans, $payData, $skipTransValidate = false)
    {
        if ($trans->status != TransactionEnums::pendingStatus)
            abort(200, "Transaction has been previously processed");
        switch ($trans->transaction_type) {
            case SubscriptionEnums::planRenewalCode:
            case SubscriptionEnums::planPurchaseCode:
                $this->subsPlanRepo->processPlanPurchase($trans->account, $trans->id);
                break;
            default:
                abort(400, "Invalid request");
                break;
        }
        $trans->update($payData);
        return ApiResponse::success("Transaction processed successfully", $trans);
    }
}
