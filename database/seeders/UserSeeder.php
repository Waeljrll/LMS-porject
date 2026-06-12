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
        $instructors = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed@lms.com',
                'phone' => '+201000000002',
                'bio' => 'Senior Full-Stack Developer with 10+ years of experience in Laravel and Vue.js.',
            ],
            [
                'name' => 'Sara Mohamed',
                'email' => 'sara@lms.com',
                'phone' => '+201000000003',
                'bio' => 'UI/UX Designer and Frontend Expert specializing in React and modern CSS.',
            ],
            [
                'name' => 'Omar Khaled',
                'email' => 'omar@lms.com',
                'phone' => '+201000000004',
                'bio' => 'Data Scientist and Python instructor with a PhD in Machine Learning.',
            ],
        ];

        foreach ($instructors as $instructor) {
            User::create([
                'name' => $instructor['name'],
                'email' => $instructor['email'],
                'password' => Hash::make('password'),
                'role' => 'instructor',
                'status' => 'active',
                'phone' => $instructor['phone'],
                'bio' => $instructor['bio'],
                'profile_picture' => null,
            ]);
        }

        // Students
        $students = [
            ['name' => 'Mohamed Ali', 'email' => 'mohamed@lms.com'],
            ['name' => 'Fatma Ibrahim', 'email' => 'fatma@lms.com'],
            ['name' => 'Khaled Samir', 'email' => 'khaled@lms.com'],
            ['name' => 'Nour Ahmed', 'email' => 'nour@lms.com'],
            ['name' => 'Youssef Tarek', 'email' => 'youssef@lms.com'],
            ['name' => 'Mariam Hossam', 'email' => 'mariam@lms.com'],
            ['name' => 'Hassan Mostafa', 'email' => 'hassan@lms.com'],
            ['name' => 'Laila Mahmoud', 'email' => 'laila@lms.com'],
            ['name' => 'Tarek Adel', 'email' => 'tarek@lms.com'],
            ['name' => 'Rana Sayed', 'email' => 'rana@lms.com'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'active',
                'phone' => null,
                'bio' => null,
                'profile_picture' => null,
            ]);
        }
    }
}
