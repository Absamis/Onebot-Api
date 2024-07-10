<?php

namespace App\Http\Resources\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountInvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "userid" => $this->userid,
            "account" => $this->account,
            "email" => $this->email,
            "name" => $this->name,
            "role" => $this->role,
            "status" => $this->status,
        ];
    }
}
