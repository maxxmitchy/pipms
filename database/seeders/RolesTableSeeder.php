<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'admin', 'description' => 'Administrator']);
        Role::create(['name' => 'pharmacist', 'description' => 'Pharmacist']);
        Role::create(['name' => 'doctor', 'description' => 'Doctor']);
        Role::create(['name' => 'nurse', 'description' => 'Nurse']);
    }
}
