<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = [
            // Mohamed Ali
            ['student_id' => 5, 'course_id' => 1, 'progress' => 75, 'status' => 'active'],
            ['student_id' => 5, 'course_id' => 2, 'progress' => 30, 'status' => 'active'],
            ['student_id' => 5, 'course_id' => 3, 'progress' => 100, 'status' => 'completed', 'completed' => '2026-05-20 14:30:00'],

            // Fatma Ibrahim
            ['student_id' => 6, 'course_id' => 2, 'progress' => 90, 'status' => 'active'],
            ['student_id' => 6, 'course_id' => 4, 'progress' => 45, 'status' => 'active'],
            ['student_id' => 6, 'course_id' => 1, 'progress' => 100, 'status' => 'completed', 'completed' => '2026-05-15 10:00:00'],

            // Khaled Samir
            ['student_id' => 7, 'course_id' => 3, 'progress' => 60, 'status' => 'active'],
            ['student_id' => 7, 'course_id' => 5, 'progress' => 20, 'status' => 'active'],
            ['student_id' => 7, 'course_id' => 7, 'progress' => 10, 'status' => 'active'],

            // Nour Ahmed
            ['student_id' => 8, 'course_id' => 4, 'progress' => 100, 'status' => 'completed', 'completed' => '2026-05-18 16:45:00'],
            ['student_id' => 8, 'course_id' => 2, 'progress' => 55, 'status' => 'active'],

            // Youssef Tarek
            ['student_id' => 9, 'course_id' => 1, 'progress' => 40, 'status' => 'active'],
            ['student_id' => 9, 'course_id' => 5, 'progress' => 80, 'status' => 'active'],
            ['student_id' => 9, 'course_id' => 6, 'progress' => 15, 'status' => 'active'],

            // Mariam Hossam
            ['student_id' => 10, 'course_id' => 6, 'progress' => 100, 'status' => 'completed', 'completed' => '2026-05-22 09:15:00'],
            ['student_id' => 10, 'course_id' => 2, 'progress' => 65, 'status' => 'active'],

            // Hassan Mostafa
            ['student_id' => 11, 'course_id' => 7, 'progress' => 50, 'status' => 'active'],
            ['student_id' => 11, 'course_id' => 3, 'progress' => 25, 'status' => 'active'],

            // Laila Mahmoud
            ['student_id' => 12, 'course_id' => 4, 'progress' => 35, 'status' => 'active'],
            ['student_id' => 12, 'course_id' => 1, 'progress' => 100, 'status' => 'completed', 'completed' => '2026-05-25 11:30:00'],

            // Tarek Adel
            ['student_id' => 13, 'course_id' => 5, 'progress' => 100, 'status' => 'completed', 'completed' => '2026-05-28 13:00:00'],
            ['student_id' => 13, 'course_id' => 7, 'progress' => 70, 'status' => 'active'],

            // Rana Sayed
            ['student_id' => 14, 'course_id' => 3, 'progress' => 85, 'status' => 'active'],
            ['student_id' => 14, 'course_id' => 6, 'progress' => 45, 'status' => 'active'],
        ];

        foreach ($enrollments as $enrollment) {
            $data = [
                'student_id' => $enrollment['student_id'],
                'course_id' => $enrollment['course_id'],
                'progress_percentage' => $enrollment['progress'],
                'status' => $enrollment['status'],
            ];

            if (isset($enrollment['completed'])) {
                $data['completed_at'] = $enrollment['completed'];
            }

            Enrollment::create($data);
        }
    }
}
