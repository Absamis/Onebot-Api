<?php

namespace Database\Seeders;

use App\Models\Channels\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Contact::create([
            "account_id" => 1,
            "channel_id" => 1,
            "name" => "Adebiyi Absam",
            "contact_app_id" => "344rr4455",
            "token" => null,
            "email" => "smallkid1@yahoo.com",
            "phone" => "09098989898",
            "photo" => null,
            "gender" => null,
            "locale" => null,
            "contact_app_type" => "fb",
            "conversation_assigned_to" => null,
            "conversation_status" => "unassigned",
            "status" => "active"
        ]);
    }
}
