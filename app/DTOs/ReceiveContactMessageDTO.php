<?php

namespace App\DTOs;

class ReceiveContactMessageDTO
{
    /**
     * Create a new class instance.
     */
    public $id;
    public $date;
    public $time;
    public $conv_type;
    public $sender;
    public $message;
    public $attached;
    public $status;
    public $channel;
    public $receiver;
    public function __construct(
        $id,
        $date,
        $time,
        $conv_type,
        $message,
        $channel,
        $status,
        $sender = null,
        $receiver = null,
        $attached = null
    ) {
        //
        $this->id = $id;
        $this->date = $date;
        $this->time = $time;
        $this->conv_type = $conv_type;
        $this->sender = $sender;
        $this->message = $message;
        $this->status = $status;
        $this->attached = $attached;
        $this->receiver = $receiver;
        $this->channel = $channel;
    }
}
