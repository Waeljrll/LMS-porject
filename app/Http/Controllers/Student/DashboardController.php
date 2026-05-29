<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $enrolledCourses = $user->enrollments()
            ->with(['course.lessons', 'course.instructor'])
            ->get();

        $totalEnrolled = $enrolledCourses->count();
        $completedCourses = $enrolledCourses->where('status', 'completed')->count();
        $inProgressCourses = $enrolledCourses->where('status', 'active')->count();
        $certificatesEarned = $completedCourses;

        $enrolledCourses->transform(function ($enrollment) use ($user) {
            if ($enrollment->status === 'active') {
                $lessons = $enrollment->course->lessons;
                $totalLessons = $lessons->count();

                if ($totalLessons > 0) {
                    $lessonIds = $lessons->pluck('id');

                    $completedLessonsCount = LessonProgress::where('student_id', $user->id)
                        ->whereIn('lesson_id', $lessonIds)
                        ->where('is_completed', true)
                        ->count();

                    $enrollment->total_lessons_count = $totalLessons;
                    $enrollment->completed_lessons_count = $completedLessonsCount;
                    $enrollment->actual_progress = round(($completedLessonsCount / $totalLessons) * 100);
                } else {
                    $enrollment->total_lessons_count = 0;
                    $enrollment->completed_lessons_count = 0;
                    $enrollment->actual_progress = 0;
                }
            } else {
                $enrollment->actual_progress = $enrollment->status === 'completed' ? 100 : 0;
                $enrollment->total_lessons_count = $enrollment->course->lessons->count();
                $enrollment->completed_lessons_count = $enrollment->status === 'completed' ? $enrollment->total_lessons_count : 0;
            }

            return $enrollment;
        });

        return view('pages.student.dashboard', compact(
            'enrolledCourses',
            'totalEnrolled',
            'completedCourses',
            'inProgressCourses',
            'certificatesEarned'
        ));
    }
}
