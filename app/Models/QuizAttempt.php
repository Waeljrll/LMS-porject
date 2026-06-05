<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id', 'student_id', 'attempt_number', 'started_at',
        'submitted_at', 'time_taken_seconds', 'score', 'total_points',
        'percentage', 'status', 'ip_address',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'percentage' => 'decimal:2',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['submitted', 'passed', 'failed']);
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isPassed(): bool
    {
        return $this->status === 'passed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function timeRemaining(): ?int
    {
        if (!$this->quiz->time_limit_minutes || !$this->isInProgress()) {
            return null;
        }
        $elapsed = now()->diffInSeconds($this->started_at);
        $limit = $this->quiz->time_limit_minutes * 60;
        return max(0, $limit - $elapsed);
    }

    public function formattedTimeTaken(): string
    {
        if (!$this->time_taken_seconds) return 'N/A';
        $minutes = floor($this->time_taken_seconds / 60);
        $seconds = $this->time_taken_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getAnswerForQuestion(int $questionId): ?QuizAnswer
    {
        return $this->answers()->where('question_id', $questionId)->first();
    }
}
