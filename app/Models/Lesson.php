<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'slug',
        'duration_minutes',
        'lesson_type',
        'video_url',
        'text_content',
        'is_preview',
        'order_number'
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
    public function videoUrl()
    {
        if (!$this->video_url) {
            return null;
        }

        if (filter_var($this->video_url, FILTER_VALIDATE_URL)) {
            return $this->video_url;
        }

        return asset("storage/{$this->video_url}");
    }
}
