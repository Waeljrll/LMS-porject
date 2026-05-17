<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'instructor_id',
        'title',
        'slug',
        'short_description',
        'description',
        'difficulty_level',
        'language',
        'thumbnail',
        'price',
        'status',
        'requirements',
        'who_is_it_for',
        'duration_hours',
        'duration_minutes',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function objectives()
    {
        return $this->hasMany(CourseObjective::class);
    }
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
                return $this->thumbnail;
            }
            return asset("storage/{$this->thumbnail}");
        }

        return asset("assets/img/course-placeholder.jpg");
    }


    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('sort_order', 'asc');
    }


    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Section::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
