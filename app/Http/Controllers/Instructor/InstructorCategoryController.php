<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class InstructorCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('courses')->get();
        return view('pages.instructor.categories.index', compact('categories'));
    }
}
