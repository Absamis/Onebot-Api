<?php

namespace App\Http\Resources\Channels;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
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
            "name" => $this->name,
            "type" => $this->type,
            "photo" => $this->photo,
            "description" => $this->description,
            "channel_app_id" => $this->channel_app_id,
            "no_of_contacts" => $this->contacts->count(),
            "status" => $this->status,
        ];
    }
}
