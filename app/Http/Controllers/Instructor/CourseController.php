<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        return view('pages.instructor.courses.index');
    }
    public function analytics(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403, 'عذراً، لا تملك صلاحية عرض تحليلات هذا الكورس.');
        }

        $totalEnrollments = $course->enrollments()->count();

        $activeStudentsCount = $course->enrollments()
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();

        $completedEnrollmentsCount = $course->enrollments()->where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0
            ? round(($completedEnrollmentsCount / $totalEnrollments) * 100, 1)
            : 0;

        $averageProgress = $totalEnrollments > 0
            ? round($course->enrollments()->avg('progress_percentage'), 1)
            : 0;

        $enrollments = $course->enrollments()
            ->with('student')
            ->latest('enrolled_at')
            ->paginate(10);

        return view('pages.instructor.courses.analytics', compact(
            'course',
            'totalEnrollments',
            'activeStudentsCount',
            'completionRate',
            'averageProgress',
            'enrollments'
        ));
    }
}
