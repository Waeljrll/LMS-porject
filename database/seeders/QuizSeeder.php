<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Quiz 1: Course 1 - Section 2 (Routing & Controllers)
        $quiz1 = Quiz::create([
            'section_id' => 2,
            'title' => 'Laravel Routing & Controllers Quiz',
            'description' => 'Test your knowledge of Laravel routing system and controller patterns.',
            'instructions' => 'Answer all questions. You need 70% to pass. Time limit: 15 minutes.',
            'time_limit_minutes' => 15,
            'passing_score_percentage' => 70,
            'max_attempts' => 3,
            'shuffle_questions' => true,
            'show_correct_answers' => 'after_completion',
            'is_published' => true,
            'created_by' => 2,
        ]);

        $q1 = QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'Which method is used to define a GET route in Laravel?',
            'question_type' => 'mcq',
            'points' => 2,
            'explanation' => 'Route::get() is the standard method for defining GET routes in Laravel.',
            'sort_order' => 1,
        ]);
        QuizOption::create(['question_id' => $q1->id, 'option_text' => 'Route::get()', 'is_correct' => true, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q1->id, 'option_text' => 'Route::post()', 'is_correct' => false, 'sort_order' => 2]);
        QuizOption::create(['question_id' => $q1->id, 'option_text' => 'Route::fetch()', 'is_correct' => false, 'sort_order' => 3]);
        QuizOption::create(['question_id' => $q1->id, 'option_text' => 'Route::load()', 'is_correct' => false, 'sort_order' => 4]);

        $q2 = QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'A resource controller automatically provides routes for CRUD operations.',
            'question_type' => 'true_false',
            'points' => 1,
            'explanation' => 'Resource controllers in Laravel automatically map to CRUD routes.',
            'sort_order' => 2,
        ]);
        QuizOption::create(['question_id' => $q2->id, 'option_text' => 'True', 'is_correct' => true, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q2->id, 'option_text' => 'False', 'is_correct' => false, 'sort_order' => 2]);

        $q3 = QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question_text' => 'Which artisan command creates a new controller?',
            'question_type' => 'mcq',
            'points' => 2,
            'explanation' => 'php artisan make:controller is the correct command to generate a new controller.',
            'sort_order' => 3,
        ]);
        QuizOption::create(['question_id' => $q3->id, 'option_text' => 'php artisan create:controller', 'is_correct' => false, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q3->id, 'option_text' => 'php artisan make:controller', 'is_correct' => true, 'sort_order' => 2]);
        QuizOption::create(['question_id' => $q3->id, 'option_text' => 'php artisan generate:controller', 'is_correct' => false, 'sort_order' => 3]);
        QuizOption::create(['question_id' => $q3->id, 'option_text' => 'php artisan new:controller', 'is_correct' => false, 'sort_order' => 4]);

        // Quiz 2: Course 1 - Section 3 (Database & Eloquent)
        $quiz2 = Quiz::create([
            'section_id' => 3,
            'title' => 'Eloquent ORM Mastery Quiz',
            'description' => 'Test your understanding of Eloquent ORM, relationships, and query optimization.',
            'instructions' => 'Answer all questions carefully. Time limit: 20 minutes.',
            'time_limit_minutes' => 20,
            'passing_score_percentage' => 75,
            'max_attempts' => 3,
            'shuffle_questions' => true,
            'show_correct_answers' => 'after_completion',
            'is_published' => true,
            'created_by' => 2,
        ]);

        $q4 = QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'Which Eloquent relationship defines a one-to-many relationship?',
            'question_type' => 'mcq',
            'points' => 2,
            'explanation' => 'hasMany() defines a one-to-many relationship in Eloquent.',
            'sort_order' => 1,
        ]);
        QuizOption::create(['question_id' => $q4->id, 'option_text' => 'hasOne()', 'is_correct' => false, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q4->id, 'option_text' => 'hasMany()', 'is_correct' => true, 'sort_order' => 2]);
        QuizOption::create(['question_id' => $q4->id, 'option_text' => 'belongsTo()', 'is_correct' => false, 'sort_order' => 3]);
        QuizOption::create(['question_id' => $q4->id, 'option_text' => 'manyToMany()', 'is_correct' => false, 'sort_order' => 4]);

        $q5 = QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'Eager loading helps prevent the N+1 query problem.',
            'question_type' => 'true_false',
            'points' => 1,
            'explanation' => 'Eager loading with with() prevents N+1 queries by loading relationships in advance.',
            'sort_order' => 2,
        ]);
        QuizOption::create(['question_id' => $q5->id, 'option_text' => 'True', 'is_correct' => true, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q5->id, 'option_text' => 'False', 'is_correct' => false, 'sort_order' => 2]);

        $q6 = QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question_text' => 'What does the fillable property in an Eloquent model do?',
            'question_type' => 'mcq',
            'points' => 2,
            'explanation' => 'The fillable array specifies which attributes can be mass-assigned.',
            'sort_order' => 3,
        ]);
        QuizOption::create(['question_id' => $q6->id, 'option_text' => 'Validates input data', 'is_correct' => false, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q6->id, 'option_text' => 'Specifies mass-assignable attributes', 'is_correct' => true, 'sort_order' => 2]);
        QuizOption::create(['question_id' => $q6->id, 'option_text' => 'Casts data types automatically', 'is_correct' => false, 'sort_order' => 3]);
        QuizOption::create(['question_id' => $q6->id, 'option_text' => 'Defines database indexes', 'is_correct' => false, 'sort_order' => 4]);

        // Quiz 3: Course 2 - Section 6 (React Fundamentals)
        $quiz3 = Quiz::create([
            'section_id' => 6,
            'title' => 'React Fundamentals Quiz',
            'description' => 'Test your understanding of React basics including components, props, and state.',
            'instructions' => 'Answer all questions. Time limit: 10 minutes.',
            'time_limit_minutes' => 10,
            'passing_score_percentage' => 70,
            'max_attempts' => 3,
            'shuffle_questions' => false,
            'show_correct_answers' => 'immediately',
            'is_published' => true,
            'created_by' => 3,
        ]);

        $q7 = QuizQuestion::create([
            'quiz_id' => $quiz3->id,
            'question_text' => 'Which hook is used to manage state in functional components?',
            'question_type' => 'mcq',
            'points' => 2,
            'explanation' => 'useState is the primary hook for managing state in React functional components.',
            'sort_order' => 1,
        ]);
        QuizOption::create(['question_id' => $q7->id, 'option_text' => 'useEffect', 'is_correct' => false, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q7->id, 'option_text' => 'useState', 'is_correct' => true, 'sort_order' => 2]);
        QuizOption::create(['question_id' => $q7->id, 'option_text' => 'useContext', 'is_correct' => false, 'sort_order' => 3]);
        QuizOption::create(['question_id' => $q7->id, 'option_text' => 'useReducer', 'is_correct' => false, 'sort_order' => 4]);

        $q8 = QuizQuestion::create([
            'quiz_id' => $quiz3->id,
            'question_text' => 'Props can be modified by the child component.',
            'question_type' => 'true_false',
            'points' => 1,
            'explanation' => 'Props are read-only and cannot be modified by child components. Use state or callbacks for interactivity.',
            'sort_order' => 2,
        ]);
        QuizOption::create(['question_id' => $q8->id, 'option_text' => 'True', 'is_correct' => false, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q8->id, 'option_text' => 'False', 'is_correct' => true, 'sort_order' => 2]);

        $q9 = QuizQuestion::create([
            'quiz_id' => $quiz3->id,
            'question_text' => 'JSX stands for...',
            'question_type' => 'mcq',
            'points' => 1,
            'explanation' => 'JSX stands for JavaScript XML, a syntax extension for JavaScript.',
            'sort_order' => 3,
        ]);
        QuizOption::create(['question_id' => $q9->id, 'option_text' => 'JavaScript Extension', 'is_correct' => false, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q9->id, 'option_text' => 'JavaScript XML', 'is_correct' => true, 'sort_order' => 2]);
        QuizOption::create(['question_id' => $q9->id, 'option_text' => 'JSON XML', 'is_correct' => false, 'sort_order' => 3]);
        QuizOption::create(['question_id' => $q9->id, 'option_text' => 'Java Syntax Extension', 'is_correct' => false, 'sort_order' => 4]);

        // Quiz 4: Course 3 - Section 11 (NumPy & Pandas)
        $quiz4 = Quiz::create([
            'section_id' => 11,
            'title' => 'NumPy & Pandas Essentials Quiz',
            'description' => 'Test your data manipulation skills with NumPy and Pandas.',
            'instructions' => 'Answer all questions. Time limit: 25 minutes.',
            'time_limit_minutes' => 25,
            'passing_score_percentage' => 65,
            'max_attempts' => 3,
            'shuffle_questions' => true,
            'show_correct_answers' => 'after_completion',
            'is_published' => true,
            'created_by' => 4,
        ]);

        $q10 = QuizQuestion::create([
            'quiz_id' => $quiz4->id,
            'question_text' => 'Which Pandas method is used to handle missing values?',
            'question_type' => 'mcq',
            'points' => 2,
            'explanation' => 'fillna() is used to fill or handle missing (NaN) values in a DataFrame.',
            'sort_order' => 1,
        ]);
        QuizOption::create(['question_id' => $q10->id, 'option_text' => 'dropna()', 'is_correct' => false, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q10->id, 'option_text' => 'fillna()', 'is_correct' => true, 'sort_order' => 2]);
        QuizOption::create(['question_id' => $q10->id, 'option_text' => 'clean()', 'is_correct' => false, 'sort_order' => 3]);
        QuizOption::create(['question_id' => $q10->id, 'option_text' => 'replace()', 'is_correct' => false, 'sort_order' => 4]);

        $q11 = QuizQuestion::create([
            'quiz_id' => $quiz4->id,
            'question_text' => 'NumPy arrays can only contain elements of the same data type.',
            'question_type' => 'true_false',
            'points' => 1,
            'explanation' => 'NumPy arrays are homogeneous, meaning all elements must be of the same data type.',
            'sort_order' => 2,
        ]);
        QuizOption::create(['question_id' => $q11->id, 'option_text' => 'True', 'is_correct' => true, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q11->id, 'option_text' => 'False', 'is_correct' => false, 'sort_order' => 2]);

        $q12 = QuizQuestion::create([
            'quiz_id' => $quiz4->id,
            'question_text' => 'Which method merges two DataFrames based on a common column?',
            'question_type' => 'mcq',
            'points' => 2,
            'explanation' => 'merge() is the Pandas method used to join DataFrames similar to SQL joins.',
            'sort_order' => 3,
        ]);
        QuizOption::create(['question_id' => $q12->id, 'option_text' => 'concat()', 'is_correct' => false, 'sort_order' => 1]);
        QuizOption::create(['question_id' => $q12->id, 'option_text' => 'join()', 'is_correct' => false, 'sort_order' => 2]);
        QuizOption::create(['question_id' => $q12->id, 'option_text' => 'merge()', 'is_correct' => true, 'sort_order' => 3]);
        QuizOption::create(['question_id' => $q12->id, 'option_text' => 'combine()', 'is_correct' => false, 'sort_order' => 4]);

        // Create a sample quiz attempt for student 5 (Mohamed Ali)
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz1->id,
            'student_id' => 5,
            'attempt_number' => 1,
            'started_at' => now()->subMinutes(20),
            'submitted_at' => now()->subMinutes(5),
            'time_taken_seconds' => 900,
            'score' => 4,
            'total_points' => 5,
            'percentage' => 80.00,
            'status' => 'passed',
            'ip_address' => '127.0.0.1',
        ]);

        // Create answers for the attempt
        QuizAnswer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $q1->id,
            'selected_option_id' => 1,
            'is_correct' => true,
            'points_earned' => 2,
        ]);

        QuizAnswer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $q2->id,
            'selected_option_id' => 5,
            'is_correct' => true,
            'points_earned' => 1,
        ]);

        QuizAnswer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $q3->id,
            'selected_option_id' => 7,
            'is_correct' => true,
            'points_earned' => 2,
        ]);
    }
}
