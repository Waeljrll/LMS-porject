<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $courses = Course::with(['category', 'instructor'])->latest()->paginate(10);
            return view('pages.admin.courses.index', compact('courses'));
        }

        $courses = Course::with(['category', 'instructor'])
            ->where('status', 'published')
            ->latest()
            ->paginate(9); // 3 per row

        return view('pages.student.courses.index', compact('courses'));
    }

    public function myCourses()
    {
        $courses = Course::with(['category'])
            ->where('instructor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.instructor.courses.index', compact('courses'));
    }

    public function create()
    {
        if (Auth::user()->isStudent()) {
            abort(403);
        }
        return view('pages.admin.courses.create');
    }

    public function edit(Course $course)
    {
        if (Auth::user()->isInstructor() && $course->instructor_id !== Auth::id()) {
            abort(403, 'You can only edit your own courses.');
        }

        return view('pages.admin.courses.edit', compact('course'));
    }

    public function destroy(Course $course)
    {
        if (Auth::user()->isInstructor() && $course->instructor_id !== Auth::id()) {
            abort(403, 'You can only delete your own courses.');
        }

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->back()->with('success', 'Course deleted successfully');
    }

    public function content(Course $course)
    {
        if (Auth::user()->isInstructor() && $course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('pages.admin.courses.content', compact('course'));
    }
}
