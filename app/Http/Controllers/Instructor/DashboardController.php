<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myCourses = $user->courses()
            ->with('category')
            ->withCount(['enrollments', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        $totalCourses = $myCourses->count();
        $draftCourses = $myCourses->where('status', 'draft')->count();
        $publishedCourses = $myCourses->where('status', 'published')->count();

        $totalStudents = $myCourses->sum('enrollments_count');
        $totalRevenue = $myCourses->sum('price');
        $avgRating = $myCourses->avg('reviews_avg_rating') ?? 0;

        // 3. آخر التسجيلات (Recent Enrollments)
        $recentEnrollments = Enrollment::whereIn('course_id', $myCourses->pluck('id'))
            ->with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.instructor.dashboard', compact(
            'myCourses',
            'totalCourses',
            'totalStudents',
            'avgRating',
            'draftCourses',
            'publishedCourses',
            'totalRevenue',
            'recentEnrollments'
        ));
    }
}
