<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ExpertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'title' => 'Senior Plant Pathologist',
            'specialization' => 'Plant Pathology',
            'organization' => 'Agricultural Research Institute',
            'phone_number' => '+1 (555) 123-4567',
            'is_verified' => true,
        ]);

        User::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john.smith@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'title' => 'Agricultural Scientist',
            'specialization' => 'Agronomy',
            'organization' => 'National Agricultural University',
            'phone_number' => '+91 98765 43210',
            'is_verified' => true,
        ]);
    }
}
