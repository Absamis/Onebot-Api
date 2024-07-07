<?php

namespace App\Notifications;

use App\Models\Transactions\Transaction;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletFundedNotification extends Notification implements ShouldQueue
{
    use Queueable, NotificationTrait;

    /**
     * Create a new notification instance.
     */
    public $data;
    public function __construct(Transaction $data)
    {
        //
        $this->data = $data;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Wallet Funded")
            ->view("mail.send-wallet-funded", [
                "data" => $this->data
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
