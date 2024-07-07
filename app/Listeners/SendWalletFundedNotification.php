<?php

namespace App\Listeners;

use App\Notifications\WalletFundedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendWalletFundedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public $tries = 3;

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        $trans = $event->transaction;
        $user = $trans->user;
        Notification::send($user, new WalletFundedNotification($trans));
    }
}
