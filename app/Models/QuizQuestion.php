<?php

namespace App\Models;

use App\Models\QuizAnswer;
use App\Models\QuizOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id', 'question_text', 'question_image', 'image_path',
        'question_type', 'points', 'explanation', 'sort_order',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuizOption::class, 'question_id')->orderBy('sort_order');
    }

    public function correctOption(): ?QuizOption
    {
        return $this->options()->where('is_correct', true)->first();
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }

    public function isMcq(): bool
    {
        return $this->question_type === 'mcq';
    }

    public function isTrueFalse(): bool
    {
        return $this->question_type === 'true_false';
    }

    public function isEssay(): bool
    {
        return $this->question_type === 'essay';
    }

    public function getShuffledOptions()
    {
        return $this->options()->inRandomOrder()->get();
    }
    public function imageUrl()
{
    if ($this->image_path && filter_var($this->image_path, FILTER_VALIDATE_URL)) {
        return $this->image_path;
    }

    if ($this->image_path) {
        return asset("storage/{$this->image_path}");
    }

    return asset("assets/img/default-question.jpg");
}
}
