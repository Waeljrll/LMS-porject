<?php

namespace App\Livewire\Instructor;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizSettings extends Component
{
    public Quiz $quiz;

    public $time_limit_minutes;
    public $passing_score_percentage;
    public $max_attempts;
    public $show_correct_answers;
    public $shuffle_questions;
    public $is_published;

    public function mount(Quiz $quiz): void
    {
        if (Auth::user()->isInstructor() && $quiz->section->course->instructor_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الاختبار.');
        }

        $this->quiz                   = $quiz;
        $this->time_limit_minutes     = $quiz->time_limit_minutes;
        $this->passing_score_percentage = $quiz->passing_score_percentage;
        $this->max_attempts           = $quiz->max_attempts;
        $this->show_correct_answers   = $quiz->show_correct_answers ?? 'after_completion';
        $this->shuffle_questions      = (bool) $quiz->shuffle_questions;
        $this->is_published           = (bool) $quiz->is_published;
    }

    public function saveSettings(): void
    {
        $validated = $this->validate([
            'time_limit_minutes'       => 'nullable|integer|min:0',
            'passing_score_percentage' => 'required|numeric|between:0,100',
            'max_attempts'             => 'required|integer|min:0',
            'show_correct_answers'     => 'required|in:immediately,after_completion,never',
            'shuffle_questions'        => 'boolean',
            'is_published'             => 'boolean',
        ]);

        $this->quiz->update($validated);

        session()->flash('success', 'تم حفظ إعدادات الاختبار بنجاح.');
    }

    public function render()
    {
        return view('livewire.instructor.quiz-settings');
    }
}
