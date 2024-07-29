<?php

namespace App\DTOs;

class PayGatewayData
{
    /**
     * Create a new class instance.
     */
    public $url;
    public $reference;
    public function __construct($url, $reference)
    {
        //
        $this->url = $url;
        $this->reference = $reference;
    }
}
