<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone,
            "photo" => $this->photo,
            "status" => $this->status,
            "access_token" => $this->when($this->access_token, $this->access_token),
            "accounts" => $this->accounts->with(["account", "roles"])->get(),
            "signin_options" => $this->signin_options
        ];
    }
}
