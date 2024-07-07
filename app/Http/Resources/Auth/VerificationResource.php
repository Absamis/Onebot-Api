<?php

namespace App\Http\Resources\Auth;

use App\Enums\AccountEnums;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "token" => $this->token,
            "resend_token" => $this->when($this->verification_type != AccountEnums::resetPasswordVerificationType, $this->refresh_token)
        ];
    }
}
