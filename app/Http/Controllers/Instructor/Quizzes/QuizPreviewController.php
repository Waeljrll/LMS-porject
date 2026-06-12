<?php

namespace App\Http\Controllers\Instructor\Quizzes;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizPreviewController extends Controller
{
    public function index(Quiz $quiz)
    {
        $quiz->load([
            'section.course',
            'questions.options',
        ]);

        if (Auth::user()->isInstructor() && $quiz->section->course->instructor_id !== Auth::id()) {
            abort(403, 'You are not authorized to preview this quiz.');
        }

        // Get questions with optional shuffling (same logic as student quiz)
        $questions = $quiz->shuffle_questions
            ? $quiz->questions->shuffle()
            : $quiz->questions;

        // Calculate total time limit in seconds for JS timer
        $timeLimitSeconds = $quiz->time_limit_minutes ? $quiz->time_limit_minutes * 60 : null;

        return view('pages.instructor.quizzes.preview', compact(
            'quiz',
            'questions',
            'timeLimitSeconds'
        ));
    }
}
