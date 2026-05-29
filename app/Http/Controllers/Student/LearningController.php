<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{

    public function show(Course $course, ?Lesson $lesson = null)
    {
        $user = Auth::user();

        $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
        if (!$enrollment) {
            return redirect()->route('student.courses.show', $course)->with('error', 'يجب الاشتراك أولاً.');
        }

        $allLessons = $course->lessons()->orderBy('order_number')->get()->values();

        if ($allLessons->isEmpty()) {
            return redirect()->route('student.enrollments.index')
                ->with('info', 'هذا الكورس لا يحتوي على أي محتوى تعليمي حتى الآن.');
        }

        if (!$lesson && request()->has('lesson')) {
            $lesson = $allLessons->firstWhere('id', (int) request('lesson'));
        }

        if (!$lesson) {
            $lesson = $allLessons->first();
        }

        $currentIndex = $allLessons->search(fn($item) => $item->id === $lesson->id);
        if ($currentIndex === false) abort(404);

        $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < ($allLessons->count() - 1) ? $allLessons[$currentIndex + 1] : null;

        $sections = $course->sections()->with(['lessons' => fn($q) => $q->orderBy('order_number')])->get();

        return view('pages.student.courses.learn', compact(
            'course',
            'lesson',
            'sections',
            'previousLesson',
            'nextLesson',
            'enrollment'
        ));
    }
}
