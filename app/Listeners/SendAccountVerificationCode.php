<?php

namespace App\Listeners;

use App\Events\UserRegisteredWithEmail;
use App\Notifications\VerificationCodeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendAccountVerificationCode implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        $user = $event->user ?? auth()->user();
        $data = $event->data;
        Notification::send($user, new VerificationCodeNotification($data));
    }
}
