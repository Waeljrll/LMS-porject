<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function storeSection(StoreSectionRequest $request, Course $course)
    {
        if (Auth::user()->isInstructor() && $course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validated();

        $order = $course->sections()->count() + 1;

        $course->sections()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'sort_order' => $order
        ]);

        return redirect()->back()->with('success', 'تم إضافة الفصل بنجاح!');
    }

    public function reorderSection(Section $section, $direction)
    {
        $currentOrder = $section->sort_order;

        if ($direction === 'up' && $currentOrder > 1) {
            $previousSection = Section::where('course_id', $section->course_id)
                ->where('sort_order', $currentOrder - 1)
                ->first();

            if ($previousSection) {
                $previousSection->update(['sort_order' => $currentOrder]);
                $section->update(['sort_order' => $currentOrder - 1]);
            }
        } elseif ($direction === 'down') {
            $nextSection = Section::where('course_id', $section->course_id)
                ->where('sort_order', $currentOrder + 1)
                ->first();

            if ($nextSection) {
                $nextSection->update(['sort_order' => $currentOrder]);
                $section->update(['sort_order' => $currentOrder + 1]);
            }
        }

        return redirect()->back()->with('success', 'تم تحديث ترتيب الفصول!');
    }
    public function updateSection(UpdateSectionRequest $request, Section $section)
    {
        if (auth()->user()->role === 'instructor' && $section->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();


        $section->update([
            'title' => $validated->title,
            'description' => $validated->description,
        ]);

        return back()->with('success', 'تم تحديث الفصل بنجاح.');
    }

    public function destroySection(Section $section)
    {
        $course = $section->course;

        if (Auth::user()->isInstructor() && $course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        if ($section->lessons()->count() > 0) {
            return redirect()->back()->with('error', 'لا يمكنك حذف فصل يحتوي على دروس!');
        }

        $section->delete();
        return redirect()->back()->with('success', 'تم حذف الفصل بنجاح!');
    }
}
