<?php

namespace Database\Seeders;

use Database\Seeders\CategorySeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\EnrollmentSeeder;
use Database\Seeders\LessonProgressSeeder;
use Database\Seeders\LessonSeeder;
use Database\Seeders\QuizSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\SectionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            CourseSeeder::class,
            SectionSeeder::class,
            LessonSeeder::class,
            EnrollmentSeeder::class,
            ReviewSeeder::class,
            QuizSeeder::class,
            LessonProgressSeeder::class,

        ]);
    }
}
