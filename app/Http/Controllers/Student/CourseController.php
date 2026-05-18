<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('status', 'published');

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $courses = $query->latest()->paginate(9);
        $categories = Category::all();

        return view('pages.student.courses.index', compact('courses', 'categories'));
    }

    public function myCourses()
    {
        $courses = Auth::user()->enrollments()
            ->with('course')
            ->latest()
            ->paginate(9);

        return view('pages.student.courses.my', compact('courses'));
    }

    public function show(Course $course, Request $request)
    {
        $isPreview = $request->has('preview');

        if ($course->status !== 'published') {
            $isOwner = Auth::id() === $course->instructor_id;
            $isAdmin = Auth::user()->isAdmin();

            if (!$isOwner && !$isAdmin) {
                abort(404);
            }
        }

        if ($isPreview && Auth::id() !== $course->instructor_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('pages.student.courses.show', compact('course'));
    }

    public function enroll(Course $course)
    {
        if ($course->status !== 'published') {
            return back()->with('error', 'This course is not available for enrollment.');
        }

        // Check if already enrolled
        if (Auth::user()->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('student.courses.my')->with('info', 'You are already enrolled in this course.');
        }

        Auth::user()->enrollments()->create([
            'course_id' => $course->id,
            'status' => 'active',
            'progress_percentage' => 0,
        ]);

        return redirect()->route('student.courses.my')->with('success', 'Successfully enrolled in ' . $course->title);
    }
}
