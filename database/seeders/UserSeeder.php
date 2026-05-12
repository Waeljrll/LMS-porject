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
            'name' => 'Ahmed Hassan',
            'email' => 'ahmed@system.com',
            'password' => Hash::make('instructor123'),
            'role' => 'instructor',
            'phone' => '01111111111',
            'bio' => 'Senior PHP & Laravel Developer',
            'email_verified_at' => now(),
        ]);

        // Instructor 2
        User::create([
            'name' => 'Sara Mohamed',
            'email' => 'sara@system.com',
            'password' => Hash::make('instructor123'),
            'role' => 'instructor',
            'phone' => '01222222222',
            'bio' => 'Frontend Expert - React & Vue',
            'email_verified_at' => now(),
        ]);

        // Student 1
        User::create([
            'name' => 'Mohamed Ali',
            'email' => 'mohamed@system.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'phone' => '01333333333',
            'bio' => 'Junior Developer',
            'email_verified_at' => now(),
        ]);

        // Student 2
        User::create([
            'name' => 'Fatma Khaled',
            'email' => 'fatma@system.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'phone' => '01444444444',
            'bio' => 'CS Student',
            'email_verified_at' => now(),
        ]);
    }
}