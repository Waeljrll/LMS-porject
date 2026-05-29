<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LessonCompleteButton extends Component
{
    public Lesson $lesson;
    public Course $course;
    public bool $isCompleted = false;

    public function mount(Lesson $lesson, Course $course)
    {
        $this->lesson = $lesson;
        $this->course = $course;

        $this->isCompleted = LessonProgress::where('student_id', Auth::id())
            ->where('lesson_id', $lesson->id)
            ->where('is_completed', true)
            ->exists();
    }

    public function complete()
    {
        $user = Auth::user();
        $enrollment = $user->enrollments()->where('course_id', $this->course->id)->first();

        if (!$enrollment) return;

        LessonProgress::updateOrCreate(
            [
                'student_id'   => $user->id,
                'lesson_id'    => $this->lesson->id
            ],
            [
                'enrollment_id' => $enrollment->id,
                'is_completed' => true,
                'watched_time' => $this->lesson->duration_minutes ?? 0
            ]
        );

        $totalLessons = $this->course->lessons->count();
        $completedCount = LessonProgress::where('student_id', $user->id)
            ->where('is_completed', true)
            ->whereIn('lesson_id', $this->course->lessons->pluck('id'))
            ->count();

        $progressPercent = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

        $enrollment->update([
            'progress_percentage' => $progressPercent,
            'status' => $progressPercent == 100 ? 'completed' : 'active',
            'completed_at' => $progressPercent == 100 ? now() : null,
        ]);

        $this->isCompleted = true;

        $this->dispatch('progress-updated', progress: $progressPercent);
    }

    public function render()
    {
        return view('livewire.student.lesson-complete-button');
    }
}
