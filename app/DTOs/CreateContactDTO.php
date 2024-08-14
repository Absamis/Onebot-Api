<?php

namespace App\DTOs;

class CreateContactDTO
{
    /**
     * Create a new class instance.
     */
    public $name;
    public $photo;
    public $email;
    public $gender;
    public $phone;
    public $locale;
    public function __construct(
        $name,
        $photo,
        $locale,
        $email = null,
        $phone = null,
        $gender = null
    ) {
        //
        $this->name = $name;
        $this->email = $email;
        $this->photo = $photo;
        $this->gender = $gender;
        $this->phone = $phone;
        $this->locale = $locale;
    }
}
