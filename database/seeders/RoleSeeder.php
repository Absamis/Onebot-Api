<?php

namespace Database\Seeders;

use App\Models\Configurations\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create([
            "name" => "Owner"
        ]);
        Role::create([
            "name" => "Admin"
        ]);

    }
}
