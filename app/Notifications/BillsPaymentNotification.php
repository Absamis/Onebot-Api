<?php

namespace App\Notifications;

use App\Enums\BillingEnums;
use App\Models\Billings\BillingTransaction;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BillsPaymentNotification extends Notification
{
    use Queueable, NotificationTrait;

    /**
     * Create a new notification instance.
     */
    public $transaction;
    public function __construct(BillingTransaction $trans)
    {
        //
        $this->transaction = $trans;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = null;
        switch($this->transaction->transaction_type){
            case BillingEnums::airtimePurchaseType:
                $subject = "Airtime purchase";
                break;
            case BillingEnums::dataPurchaseType:
                $subject = "Data purchase";
                break;
            default:
            break;
        }
        return (new MailMessage)
                    ->subject($subject)
                    ->view("mail.bills-payment-receipt", [
                        "transaction" => $this->transaction,
                        "user" => $notifiable
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
