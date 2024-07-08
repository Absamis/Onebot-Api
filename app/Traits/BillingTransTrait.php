<?php
namespace App\Traits;

use App\DTOs\VTU\BillingTransactionDTO;
use App\Models\Billings\BillingTransaction;
use App\Models\ServiceProvider;
use Illuminate\Support\Str;

trait BillingTransTrait{
    public function logTransaction(BillingTransactionDTO $billDTO){
        $bill = BillingTransaction::create([
            "id" => $billDTO->id,
            "userid" => auth()->user()->id,
            "amount" => $billDTO->amount,
            "vendor_amount" => $billDTO->vendor_amount,
            "charges" => $billDTO->charges,
            "currency" => $billDTO->currency ?? appSettings()->app_currency,
            "transaction_type" => $billDTO->transaction_type,
            "vendor" => $billDTO->vendor,
            "service_type" => $billDTO->service_type,
            "reference" => $billDTO->reference,
            "service_name" => $billDTO->service_name,
            "service_id" => $billDTO->service_id,
            "recipient" => $billDTO->recipient,
            "payment_method" => $billDTO->payment_method,
            "narration" => $billDTO->narration,
            "data" => $billDTO->data,
            "status" => $billDTO->status,
        ]);
        return $bill;
    }

    public function validateProvider(ServiceProvider $prov, $cat){
        if(!Str::contains($prov->category, $cat))
            abort(400, "Invalid $cat provider selected");
        return true;
    }
}
