<?php

namespace App\Http\Controllers\Student;

use App\Actions\EnrollStudent;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    protected EnrollStudent $enrollAction;

    public function __construct(EnrollStudent $enrollAction)
    {
        $this->enrollAction = $enrollAction;
    }

    /**
     * عرض الكورسات المشترك بها الطالب
     */
    public function index()
    {
        $studentId = auth()->id();

        $activeEnrollments = Enrollment::where('student_id', $studentId)
            ->where('status', 'active')
            ->with(['course.lessons', 'course.instructor'])
            ->get();

        $completedEnrollments = Enrollment::where('student_id', $studentId)
            ->where('status', 'completed')
            ->with(['course.lessons', 'course.instructor'])
            ->get();

        return view('pages.student.courses.my-courses', compact(
            'activeEnrollments',
            'completedEnrollments'
        ));
    }

    /**
     * تسجيل الطالب في كورس جديد
     */
    public function store(Course $course)
    {


        if ($course->status !== 'published') {
            return back()->with('error', 'This course is not available for enrollment.');
        }

        if ($this->enrollAction->isEnrolled($course)) {
            return redirect()->route('student.courses.learn', $course)
                ->with('info', 'You are already enrolled in this course.');
        }

        try {
            $enrollment = $this->enrollAction->execute($course);

            return redirect()->route('student.courses.learn', $course)
                ->with('success', "Successfully enrolled in {$course->title}!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
