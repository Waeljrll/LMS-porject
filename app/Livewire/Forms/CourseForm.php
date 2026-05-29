<?php

namespace App\Livewire\Forms;

use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CourseForm extends Form
{
    // ========== Step 1 Fields ==========
    #[Validate('required|string|min:5|max:255')]
    public $title = '';

    #[Validate('required|string|min:10|max:500')]
    public $short_description = '';

    #[Validate('required|string')]
    public $description = '';

    #[Validate('required|exists:categories,id')]
    public $category_id = '';

    #[Validate('nullable|exists:users,id')]
    public $instructor_id = '';

    #[Validate('required|in:beginner,intermediate,advanced')]
    public $difficulty_level = 'beginner';

    #[Validate('required|in:English,Arabic')]
    public $language = 'English';

    #[Validate('required|in:draft,published')]
    public $status = 'draft';

    // Thumbnail (مش بيستخدم Validate attribute علشان الـ required بتتغير بين الإنشاء والتعديل)
    public $thumbnail = null;
    public $existingThumbnail = null;

    // ========== Step 2 Fields ==========
    public $requirements = '';
    public $who_is_it_for = '';

    #[Validate('nullable|integer|min:0')]
    public $duration_hours = 0;

    #[Validate('nullable|integer|min:0|max:59')]
    public $duration_minutes = 0;

    #[Validate('nullable|numeric|min:0')]
    public $price = 0;

    public $objectives = ['', '', ''];

    // ========== Populate for Edit ==========
    public function setCourse(Course $course): void
    {
        $this->title = $course->title;
        $this->short_description = $course->short_description;
        $this->description = $course->description;
        $this->category_id = $course->category_id;
        $this->instructor_id = $course->instructor_id;
        $this->difficulty_level = $course->difficulty_level;
        $this->language = $course->language ?? 'English';
        $this->status = $course->status ?? 'draft';
        $this->existingThumbnail = $course->thumbnail;
        $this->thumbnail = null;

        $this->requirements = $course->requirements ?? '';
        $this->who_is_it_for = $course->who_is_it_for ?? '';
        $this->duration_hours = $course->duration_hours ?? 0;
        $this->duration_minutes = $course->duration_minutes ?? 0;
        $this->price = $course->price ?? 0;

        $objectives = $course->objectives->pluck('objective')->toArray();
        $this->objectives = !empty($objectives) ? $objectives : ['', '', ''];
    }

    // ========== Validation Rules ==========
    public function getStep1Rules(): array
    {
        return [
            'form.title' => 'required|string|min:5|max:255',
            'form.short_description' => 'required|string|min:10|max:500',
            'form.description' => 'required|string',
            'form.category_id' => 'required|exists:categories,id',
            'form.instructor_id' => 'nullable|exists:users,id',
            'form.difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'form.language' => 'required|in:English,Arabic',
            'form.status' => 'required|in:draft,published',
            'form.thumbnail' => $this->existingThumbnail
                ? 'nullable|image|max:2048'
                : 'required|image|max:2048',
        ];
    }

    public function getAllRules(): array
    {
        return array_merge($this->getStep1Rules(), [
            'form.requirements' => 'nullable|string',
            'form.who_is_it_for' => 'nullable|string',
            'form.duration_hours' => 'nullable|integer|min:0',
            'form.duration_minutes' => 'nullable|integer|min:0|max:59',
            'form.price' => 'nullable|numeric|min:0',
            'form.objectives' => 'nullable|array',
            'form.objectives.*' => 'nullable|string|max:255',
        ]);
    }

    // ========== Store ==========
    public function store(): Course
    {
        $thumbnailPath = $this->handleThumbnail();

        $course = Course::create([
            'category_id' => $this->category_id,
            'instructor_id' => $this->instructor_id,
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'short_description' => $this->short_description,
            'description' => $this->description,
            'difficulty_level' => $this->difficulty_level,
            'language' => $this->language,
            'price' => $this->price,
            'status' => $this->status,
            'thumbnail' => $thumbnailPath,
            'requirements' => $this->requirements,
            'who_is_it_for' => $this->who_is_it_for,
            'duration_hours' => $this->duration_hours,
            'duration_minutes' => $this->duration_minutes,
        ]);

        $this->saveObjectives($course);

        return $course;
    }

    // ========== Update ==========
    public function update(Course $course): Course
    {
        $thumbnailPath = $this->handleThumbnail();
        $finalThumbnail = $thumbnailPath ?: $this->existingThumbnail;

        $course->update([
            'category_id' => $this->category_id,
            'instructor_id' => $this->instructor_id,
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'short_description' => $this->short_description,
            'description' => $this->description,
            'difficulty_level' => $this->difficulty_level,
            'language' => $this->language,
            'price' => $this->price,
            'status' => $this->status,
            'thumbnail' => $finalThumbnail,
            'requirements' => $this->requirements,
            'who_is_it_for' => $this->who_is_it_for,
            'duration_hours' => $this->duration_hours,
            'duration_minutes' => $this->duration_minutes,
        ]);

        $course->objectives()->delete();
        $this->saveObjectives($course);

        return $course;
    }

    // ========== Helpers ==========
    protected function handleThumbnail(): ?string
    {
        if ($this->thumbnail) {
            if ($this->existingThumbnail && Storage::disk('public')->exists($this->existingThumbnail)) {
                Storage::disk('public')->delete($this->existingThumbnail);
            }
            return $this->thumbnail->store('courses', 'public');
        }
        return null;
    }

    protected function saveObjectives(Course $course): void
    {
        foreach (array_filter($this->objectives) as $objectiveText) {
            if (!empty($objectiveText)) {
                $course->objectives()->create(['objective' => $objectiveText]);
            }
        }
    }
}
