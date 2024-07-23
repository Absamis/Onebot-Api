<?php

namespace App\Http\Resources\Channels;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "account" => $this->account,
            "channel" => new ChannelResource($this->channel),
            "name" => $this->name,
            "contact_app_id" => $this->contact_app_id,
            "email" => $this->email,
            "phone" => $this->phone,
            "photo" => $this->photo,
            "gender" => $this->gender,
            "locale" => $this->locale,
            "contact_app_type" => $this->contact_app_type,
            "conversations" => $this->conversations,
            "conversation_assigned_to" => $this->conversation_user,
            "conversation_status" => $this->conversation_status,
            "status" => $this->status,
        ];
    }
}
