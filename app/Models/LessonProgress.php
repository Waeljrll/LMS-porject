<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress'; // تأكد من اسم الجدول في الداتابيز

    protected $fillable = [
        'student_id',
        'lesson_id',
        'is_completed',
        'watched_time'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
