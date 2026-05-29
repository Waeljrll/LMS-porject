<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
    public function getYoutubeIdAttribute()
    {
        if ($this->lesson_type !== 'video' || empty($this->video_url)) {
            return null;
        }

        preg_match(
            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i',
            $this->video_url,
            $matches
        );

        return $matches[1] ?? null;
    }
}
