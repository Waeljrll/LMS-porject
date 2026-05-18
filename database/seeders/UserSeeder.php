<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'mohamed wael',
            'email' => 'mohamedwael011@gmail.com',
            'password' => Hash::make('50500050'),
            'role' => 'admin',
            'phone' => '01125325280',
            'bio' => 'System Administrator',
            'email_verified_at' => now(),
        ]);

        // Instructor 1
        User::create([
            'name' => 'Dina tarek',
            'email' => 'dina@system.com',
            'password' => Hash::make('50500050'),
            'role' => 'instructor',
            'phone' => '01111111111',
            'bio' => 'Senior PHP & Laravel Developer',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Maha ahmed',
            'email' => 'maha@system.com',
            'password' => Hash::make('50500050'),
            'role' => 'student',
            'phone' => '01135635380',
            'bio' => 'fresh student',
            'email_verified_at' => now(),
        ]);


    }
}
