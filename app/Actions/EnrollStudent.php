<?php

namespace App\Actions;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EnrollStudent
{
    /**
     * تسجيل الطالب في الكورس
     */
    public function execute(Course $course, ?User $user = null): Enrollment
    {
        $student = $user ?? Auth::user();

        // 1. تحقق من أن الطالب غير مسجل مسبقاً
        $existing = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        // 2. التحقق من أن الكورس منشور
        if ($course->status !== 'published') {
            throw new \Exception('This course is not available for enrollment.');
        }

        // 3. إنشاء سجل التسجيل
        $enrollment = Enrollment::create([
            'student_id'   => $student->id,
            'course_id'    => $course->id,
            'status'       => 'active',
            'progress_percentage' => 0,
            'enrolled_at'  => now(),
        ]);

        return $enrollment;
    }

    /**
     * التحقق إذا كان الطالب مسجل في الكورس
     */
    public function isEnrolled(Course $course, ?User $user = null): bool
    {
        $student = $user ?? Auth::user();

        return Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();
    }
}
