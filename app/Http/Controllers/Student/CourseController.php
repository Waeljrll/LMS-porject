<?php

namespace App\Http\Controllers\Student;

use App\Actions\EnrollStudent;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    protected EnrollStudent $enrollAction;

    /**
     * Constructor with dependency injection
     */
    public function __construct(EnrollStudent $enrollAction)
    {
        $this->enrollAction = $enrollAction;
    }

    /**
     * Browse Courses Page (Livewire)
     */
    public function index()
    {
        return view('pages.student.courses.index');
    }

    /**
     * Course Details Page
     */
    public function show($id)
    {
        $course = Course::with([
            'instructor',
            'sections.lessons',
            'objectives',
            'reviews.student'
        ])->withCount('enrollments')
            ->findOrFail($id);

        $isEnrolled = false;
        if (Auth::check() && Auth::user()->isStudent()) {
            $isEnrolled = $this->enrollAction->isEnrolled($course);
        }

        return view('pages.student.courses.show', compact(
            'course',
            'isEnrolled'
        ));
    }
}
