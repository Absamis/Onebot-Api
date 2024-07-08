<?php
namespace App\Traits;

trait NotificationTrait{
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        switch ($notifiable->notify_channel) {
            case "sms":
                $channel = ["vonage"];
                break;
            case "db":
                $channel = ['database'];
                break;
            case "sms_email":
                $channel = ['mail', "vonage"];
                break;
            case "db_email":
                $channel = ["mail", "database"];
                break;
            default:
                $channel = ['mail'];
        }
        return $channel;
    }
}
