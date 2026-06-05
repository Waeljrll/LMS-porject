<?php

namespace App\Models;

use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'description',
        'instructions',
        'time_limit_minutes',
        'passing_score_percentage',
        'max_attempts',
        'shuffle_questions',
        'show_correct_answers',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'shuffle_questions' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function totalPoints(): int
    {
        return $this->questions()->sum('points');
    }

    public function questionsCount(): int
    {
        return $this->questions()->count();
    }

    public function canAttempt(User $student): bool
    {
        if ($this->max_attempts === 0) return true;
        $attemptsCount = $this->attempts()
            ->where('student_id', $student->id)
            ->where('status', '!=', 'in_progress')
            ->count();
        return $attemptsCount < $this->max_attempts;
    }

    public function remainingAttempts(User $student): int
    {
        if ($this->max_attempts === 0) return -1;
        $usedAttempts = $this->attempts()
            ->where('student_id', $student->id)
            ->where('status', '!=', 'in_progress')
            ->count();
        return max(0, $this->max_attempts - $usedAttempts);
    }

    public function getStudentAttempts(User $student)
    {
        return $this->attempts()
            ->where('student_id', $student->id)
            ->orderBy('attempt_number', 'desc')
            ->get();
    }
}
