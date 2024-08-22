<?php

namespace App\Actions\Telegram\Webhook;

use Lorisleiva\Actions\Concerns\AsAction;

class MessageAction
{
    use AsAction;

    public function handle($data)
    {
        // ...
        $mid = $data["message_id"];
        $from = $data["from"];
        $timestamp = $data["date"];
        $chat = $data["chat"];
        $reply_msg = $data["reply_to_message"];
        $text = $data["text"];
        $audio =  $data["audio"];
        $document = $data["document"];
        $photo = $data["photo"];
        $sticket = $data["sticker"];
        $video = $data["video"];
        $video_note = $data["video_note"];
        $voice = $data["voice"];
        $caption = $data["caption"];
        $caption_entities = $data["caption_entities"];
    }
}
