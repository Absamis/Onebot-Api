<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\InvalidateTokens;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('invalidate:tokens', function () {
    dispatch(new InvalidateTokens);
})->purpose('Invalidate tokens older than the configured expiration time')->everyMinute();
