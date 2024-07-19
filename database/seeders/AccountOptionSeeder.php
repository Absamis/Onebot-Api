<?php

namespace Database\Seeders;

use App\Models\Configurations\AccountOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AccountOption::create([
            "name" => "Facebook",
            "code" => "fb"
        ]);

        AccountOption::create([
            "name" => "Instagram",
            "code" => "ig"
        ]);

        AccountOption::create([
            "name" => "Whatsapp",
            "code" => "whatsapp"
        ]);

    }
}
