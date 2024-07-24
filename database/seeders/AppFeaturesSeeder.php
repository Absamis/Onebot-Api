<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration\AppFeature;
use App\Enums\AppEnums;

class AppFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        AppFeature::create(['name' => 'Live Chat', 'description' => 'Real-time live chat support', 'status' => AppEnums::active]);
        AppFeature::create(['name' => 'Push Notifications', 'description' => 'Real-time push notifications', 'status' => AppEnums::active]);
        AppFeature::create(['name' => 'Data Sync', 'description' => 'Real-time data synchronization', 'status' => AppEnums::active]);
        AppFeature::create(['name' => 'Activity Stream', 'description' => 'Real-time activity streams', 'status' => AppEnums::active]);
        AppFeature::create(['name' => 'Collaborative Editing', 'description' => 'Real-time collaborative document editing', 'status' => AppEnums::active]);
    }
}
