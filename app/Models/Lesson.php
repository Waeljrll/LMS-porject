<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'slug',
        'lesson_type',
        'video_source',
        'video_url',
        'video_thumbnail',
        'content',
        'duration_minutes',
        'is_preview',
        'sort_order'
    ];


    public function section()
    {
        return $this->belongsTo(Section::class);
    }


    public function completedByStudents()
    {
        return $this->belongsToMany(User::class, 'lesson_progress', 'lesson_id', 'student_id')
            ->withTimestamps();
    }
}
