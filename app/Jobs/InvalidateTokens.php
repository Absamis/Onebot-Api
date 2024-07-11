<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Verification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Enums\AppEnums;

class InvalidateTokens implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $expirationMinutes = config('app.token_expiration_minutes');
        $expiredTokens = Verification::where('status', AppEnums::active)
            ->where('created_at', '<', Carbon::now()->subMinutes($expirationMinutes))
            ->get();

        foreach ($expiredTokens as $token) {
            $token->status = AppEnums::inactive;
            $token->token = null;
            $token->refresh_token = null;
            $token->save();
        }
    }
}
