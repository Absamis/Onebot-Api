<?php

namespace App\Listeners;

use App\Mail\WelcomeMessageMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMessage implements ShouldQueue
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
        $user = $event->user;
        Mail::to($user->email)->send(new WelcomeMessageMail($user));
    }
}
