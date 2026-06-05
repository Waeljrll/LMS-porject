<?php

namespace App\Http\Controllers\Instructor\Quizzes;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;

class QuizBuilderController extends Controller
{
    public function index(Section $section)
    {
        // Authorization
        if (Auth::user()->isInstructor() && $section->course->instructor_id !== Auth::id()) {
            abort(403, 'غير مصرح لك.');
        }

        if (!$section->hasQuiz()) {
            $quiz = Quiz::create([
                'section_id' => $section->id,
                'title' => 'اختبار: ' . $section->title,
                'passing_score_percentage' => 70,
                'max_attempts' => 3,
                'created_by' => Auth::id(),
            ]);
        } else {
            $quiz = $section->quiz;
        }

        return view('pages.instructor.quizzes.builder', compact('quiz', 'section'));
    }
}
