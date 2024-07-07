<?php

namespace App\Listeners;

use App\Services\Apis\MonnifyApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateWalletAccount implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    private $mfyService;
    public function __construct(MonnifyApiService $mfy)
    {
        //
        $this->mfyService = $mfy;
    }

    public $tries = 3;

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        $user = $event->user;
        $wallet = $user->wallet;
        $walletAccts = $wallet->accounts()->active()->count();
        if($walletAccts == 0){
            $req = $this->mfyService->createReserveAccounts($user);
            $accounts = $req["accounts"] ?? exit;
            foreach($accounts as $acct){
                $user->wallet_accounts()->create([
                    "wallet_id" => $wallet->id,
                    "account_name" => $acct["accountName"],
                    "account_number" => $acct["accountNumber"],
                    "bank_name" => $acct["bankName"],
                    "bank_code" => $acct["bankCode"],
                    "provider" => "monnify",
                ]);
            }
        }
    }
}
