<?php

namespace App\Services;

class TransactionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function generateTransactionId()
    {
        return uniqid(mt_rand(001, 999));
    }
}
