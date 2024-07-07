<?php

namespace App\Notifications;

use App\Enums\AccountEnums;
use App\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationCodeNotification extends Notification implements ShouldQueue
{
    use Queueable, NotificationTrait;

    /**
     * Create a new notification instance.
     */
    public $data;
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $sbj = "Verification";
        if($this->data->verification_type == AccountEnums::accountVerificationType )
            $sbj = "Account verification";
        elseif ($this->data->verification_type == AccountEnums::passwordVerificationType)
            $sbj = "Password reset";
        elseif ($this->data->verification_type == AccountEnums::pinVerificationType)
            $sbj = "Pin reset";
        return (new MailMessage)
                ->subject($sbj)
                ->view("mail.send-verification-code", [
                    "data" => $this->data,
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
