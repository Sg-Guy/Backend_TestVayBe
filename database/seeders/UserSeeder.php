<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    User::updateOrCreate(
        ['email' => 'admin@vaybe.com'], 
        [
            'name' => 'Administrateur',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]
    );
    User::updateOrCreate(
        ['email' => 'employee@vaybe.com'], 
        [
            'name' => 'Employé',
            'password' => Hash::make('employee1234'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]
    );

    }
}
