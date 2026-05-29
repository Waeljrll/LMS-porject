<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'status',
        'progress_percent',
    ];
    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class, 'enrollment_id')->where('status', 'completed');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // --- ACCESSORS ---

    public function getTotalLessonsCountAttribute()
    {
        return $this->course->lessons->count();
    }

    public function getCompletedLessonsCountAttribute()
    {
        return $this->lessonProgress->count();
    }

    public function getActualProgressAttribute()
    {
        $total = $this->total_lessons_count;
        $completed = $this->completed_lessons_count;

        if ($total == 0) return 0;

        return round(($completed / $total) * 100);
    }
}
