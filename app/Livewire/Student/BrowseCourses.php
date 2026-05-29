<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Component;

class BrowseCourses extends Component
{
    use WithPagination;

    public $search = '';
    public $categories = [];
    public $difficulty = [];
    public $priceMax = 500;
    public $sortBy = 'newest';
    public $freeOnly = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'categories' => ['except' => []],
        'difficulty' => ['except' => []],
        'priceMax' => ['except' => 500],
        'sortBy' => ['except' => 'newest'],
        'freeOnly' => ['except' => false],
    ];

    public function updating($property)
    {
        if (in_array($property, ['search', 'categories', 'difficulty', 'priceMax', 'sortBy', 'freeOnly'])) {
            $this->resetPage();
        }
    }

    public function clearAll()
    {
        $this->reset(['search', 'categories', 'difficulty', 'priceMax', 'sortBy', 'freeOnly']);
    }

    public function render()
    {
        $query = Course::with(['category', 'instructor'])
            ->where('status', 'published');

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('instructor', function ($iq) {
                        $iq->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Categories filter
        if (!empty($this->categories)) {
            $query->whereIn('category_id', $this->categories);
        }

        // Difficulty filter
        if (!empty($this->difficulty)) {
            $query->whereIn('difficulty_level', $this->difficulty);
        }

        // Price filter
        if ($this->freeOnly) {
            $query->where('price', 0);
        } else {
            $query->where('price', '<=', $this->priceMax);
        }

        // Sorting
        switch ($this->sortBy) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
        }

        $courses = $query->paginate(9);

        // Get categories with course counts
        $allCategories = Category::withCount(['courses' => function ($q) {
            $q->where('status', 'published');
        }])->get();

        return view('livewire.student.browse-courses', [
            'courses' => $courses,
            'allCategories' => $allCategories,
        ]);
    }
}
