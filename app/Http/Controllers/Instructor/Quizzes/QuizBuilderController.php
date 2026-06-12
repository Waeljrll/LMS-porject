<?php

namespace App\Http\Controllers\Instructor\Quizzes;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuizBuilderController extends Controller
{
    public function index(Section $section)
    {
        Gate::authorize('create', [Quiz::class, $section]);

        $quiz = Quiz::firstOrCreate(
            ['section_id' => $section->id],

            [
                'title'                    => 'اختبار: ' . $section->title,
                'passing_score_percentage' => 70,
                'max_attempts'             => 3,
                'created_by'               => auth()->id(),
            ]
        );

        // 3. تمرير البيانات بأمان للـ View
        return view('pages.instructor.quizzes.builder', compact('quiz', 'section'));
    }
}
