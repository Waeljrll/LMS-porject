<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['category', 'instructor'])
            ->where('status', 'published')
            ->latest()
            ->paginate(9);

        return view('pages.student.courses.index', compact('courses'));
    }

    public function myCourses()
    {
        $courses = Auth::user()->enrollments()
            ->with('course')
            ->latest()
            ->paginate(9);

        return view('pages.student.courses.my', compact('courses'));
    }

    public function show(Course $course)
    {
        if ($course->status !== 'published') {
            abort(404);
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

        // Create enrollment
        Auth::user()->enrollments()->create([
            'course_id' => $course->id,
            'status' => 'active',
            'progress' => 0,
        ]);

        return redirect()->route('student.courses.my')->with('success', 'Successfully enrolled in ' . $course->title);
    }
}
