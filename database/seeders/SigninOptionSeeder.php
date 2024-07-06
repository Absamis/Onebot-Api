<?php

namespace Database\Seeders;

use App\Models\Configurations\SigninOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SigninOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SigninOption::create([
            "name" => "Facebook"
        ]);
        SigninOption::create([
            "name" => "Google"
        ]);
    }
}
