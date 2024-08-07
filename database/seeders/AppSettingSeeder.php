<?php

namespace Database\Seeders;

use App\Models\Configurations\AppSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AppSetting::create([
            "app_name" => "Onebot",
            "currency_code" => "USD"
        ]);
    }
}
