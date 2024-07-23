<?php

namespace App\Repository;

class BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function validateNotAllArrayFieldEmpty($data = [])
    {
        $empty = true;
        foreach ($data as $key => $val) {
            if ($val != null)
                $empty = false;
        }
        return $empty ? abort(400, "All field should not be empty. At least 1 field is required") : true;
    }
}
