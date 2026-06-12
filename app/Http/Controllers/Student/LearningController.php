<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    /**
     * Show the learning page for a specific course.
     *
     * @param Course $course Route Model Binding
     * @param Lesson|null $lesson Optional specific lesson
     */
    public function show(Course $course, ?Lesson $lesson = null)
    {

        $user = Auth::user();

        // 1. Authorization: Must be enrolled
        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()
                ->route('student.courses.show', $course)
                ->with('error', 'يجب الاشتراك أولاً.');
        }


        // 2. Check if course has any content
        if ($course->getTotalLessonsCount() === 0) {
            return redirect()
                ->route('student.enrollments.index')
                ->with('info', 'هذا الكورس لا يحتوي على أي محتوى تعليمي حتى الآن.');
        }

        $lesson = $this->resolveLesson($course, $lesson);

        if (!$course->findLesson($lesson->id)) {
            abort(404, 'الدرس غير موجود في هذا الكورس.');
        }

        // 5. Navigation (Domain Logic in Model)
        $previousLesson = $course->getPreviousLesson($lesson);
        $nextLesson = $course->getNextLesson($lesson);


        // 6. Curriculum for sidebar
        $sections = $course->sections()
            ->with(['lessons' => fn($q) => $q->orderBy('order_number')])
            ->get();

        return view('pages.student.courses.learn', compact(
            'course',
            'lesson',
            'sections',
            'previousLesson',
            'nextLesson',
            'enrollment'
        ));
    }

    /**
     * Resolve which lesson to show based on request context.
     * Extracted to private method for clarity.
     */
    private function resolveLesson(Course $course, ?Lesson $lesson): Lesson
    {
        // Priority 1: Route parameter /learn/{lesson}
        if ($lesson !== null && $lesson->section->course_id === $course->id) {
            return $lesson;
        }

        // 2. إذا لم يوجد (أو لا ينتمي للكورس)، ابحث عن أول درس
        return $course->getFirstLesson()
            ?? abort(404, 'لا يوجد دروس متاحة.');
    }
}
