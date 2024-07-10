<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            "userid" => $this->userid,
            "name" => $this->name,
            "category" => $this->category,
            "company_url" => $this->company_url,
            "description" => $this->description,
            "type" => $this->type,
            "members" => UserAccountResource::collection($this->members),
            "status" => $this->status
        ];
    }
}
