<?php

namespace Database\Seeders;

use App\Models\LessonProgress;
use Illuminate\Database\Seeder;

class LessonProgressSeeder extends Seeder
{
    public function run(): void
    {
        // Mohamed Ali - Course 1 (75% progress) - completed 12/16 lessons
        $mohamedCourse1 = [
            ['student_id' => 5, 'lesson_id' => 1, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 720],
            ['student_id' => 5, 'lesson_id' => 2, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 1080],
            ['student_id' => 5, 'lesson_id' => 3, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 900],
            ['student_id' => 5, 'lesson_id' => 4, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 600],
            ['student_id' => 5, 'lesson_id' => 5, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 1200],
            ['student_id' => 5, 'lesson_id' => 6, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 960],
            ['student_id' => 5, 'lesson_id' => 7, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 840],
            ['student_id' => 5, 'lesson_id' => 8, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 780],
            ['student_id' => 5, 'lesson_id' => 9, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 660],
            ['student_id' => 5, 'lesson_id' => 10, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 1020],
            ['student_id' => 5, 'lesson_id' => 11, 'enrollment_id' => 1, 'is_completed' => true, 'watched_time' => 540],
            ['student_id' => 5, 'lesson_id' => 12, 'enrollment_id' => 1, 'is_completed' => false, 'watched_time' => 300],
        ];
    }
}
