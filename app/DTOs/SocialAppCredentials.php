<?php

namespace App\DTOs;

class SocialAppCredentials
{
    /**
     * Create a new class instance.
     */
    public $app_id;
    public $app_key;
    public $app_secret;
    public $token;
    public $scopes;
    public $state;
    public $url;
    public $data;
    public function __construct(
        $app_id,
        $app_key,
        $app_secret,
        $token,
        $scopes,
        $state,
        $url,
        $data = [],
    ) {
        //
        $this->app_id = $app_id;
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
        $this->token = $token;
        $this->scopes = $scopes;
        $this->state = $state;
        $this->url = $url;
        $this->data = $data;
    }
}
