<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
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
    public function getOrderedLessons(): Collection
    {
        return $this->lessons()
            ->orderBy('sections.sort_order')
            ->orderBy('lessons.order_number')
            ->with('section') // Eager load for section info
            ->get();
    }

    /**
     * Find a lesson by ID within this course's context.
     * Security: Ensures lesson belongs to this course.
     */
    public function findLesson(int $lessonId): ?Lesson
    {
        return $this->getOrderedLessons()
            ->firstWhere('id', $lessonId);
    }

    /**
     * Get the first lesson of the course.
     */
    public function getFirstLesson(): ?Lesson
    {
        return $this->getOrderedLessons()->first();
    }

    /**
     * Get next lesson in sequence.
     * Returns null if current lesson is the last one.
     */
    public function getNextLesson(Lesson $currentLesson): ?Lesson
    {
        $lessons = $this->getOrderedLessons();

        $currentIndex = $lessons->search(
            fn(Lesson $lesson) => $lesson->id === $currentLesson->id
        );

        // Lesson not found in this course
        if ($currentIndex === false) {
            return null;
        }

        return $lessons->get($currentIndex + 1);
    }

    /**
     * Get previous lesson in sequence.
     * Returns null if current lesson is the first one.
     */
    public function getPreviousLesson(Lesson $currentLesson): ?Lesson
    {
        $lessons = $this->getOrderedLessons();

        $currentIndex = $lessons->search(
            fn(Lesson $lesson) => $lesson->id === $currentLesson->id
        );

        if ($currentIndex === false || $currentIndex === 0) {
            return null;
        }

        return $lessons->get($currentIndex - 1);
    }

    /**
     * Get lesson at specific index.
     */
    public function getLessonAtIndex(int $index): ?Lesson
    {
        return $this->getOrderedLessons()->get($index);
    }

    /**
     * Get total lessons count (cached accessor).
     */
    public function getTotalLessonsCount(): int
    {
        return $this->getOrderedLessons()->count();
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
