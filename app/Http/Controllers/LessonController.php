<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function createLesson(Section $section)
    {
        $course = $section->course;

        if (Auth::user()->isInstructor() && $course->instructor_id !== Auth::id()) {
            abort(403, 'عذراً لا تملك صلاحية إضافة دروس لهذا الكورس');
        }

        return view('pages.instructor.courses.create_lesson', compact('section', 'course'));
    }

    public function storeLesson(StoreLessonRequest $request, Section $section)
    {
        $validated = $request->validated();

        $order = $section->lessons()->count() + 1;

        Lesson::create([
            'section_id' => $section->id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'lesson_type' => $validated['lesson_type'],
            // لو نوع الدرس فيديو بنسيف لينك الفيديو، لو مقال بينزل نل (Null)
            'video_url' => $validated['lesson_type'] === 'video' ? $validated['video_url'] : null,
            // لو نوع الدرس مقال بنسيف الـ content، لو فيديو بينزل نل (Null)
            'content' => $validated['lesson_type'] === 'article' ? $validated['content'] : null,
            'duration_minutes' => $validated['duration_minutes'],
            'is_preview' => $request->has('is_preview'), // 1 لو متعلم عليه، 0 لو لأ
            'sort_order' => $order
        ]);

        $role = Auth::user()->isAdmin() ? 'admin' : 'instructor';

        return redirect()->route($role . '.courses.content', $section->course_id)
            ->with('success', 'تم إضافة الدرس بنجاح!');
    }
    public function editLesson(Lesson $lesson)
    {
        // حماية: لو المستخدم مدرس، نتأكد إنه صاحب الكورس
        if (auth()->user()->role === 'instructor' && $lesson->section->course->instructor_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الدرس.');
        }

        return view('pages.instructor.lessons.edit', compact('lesson'));
    }
    public function updateLesson(UpdateLessonRequest $request, Lesson $lesson)
    {
        if (auth()->user()->role === 'instructor' && $lesson->section->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        $lesson->update([
            'title' => $validated['title'],
            'duration_minutes' => $validated['duration_minutes'],
            'lesson_type' => $validated['lesson_type'],
            'is_preview' => isset($validated['is_preview']) ? $validated['is_preview'] : false,
        ]);

        return redirect()
            ->route(auth()->user()->role . '.courses.content', $lesson->section->course_id)
            ->with('success', 'تم تحديث الدرس بنجاح.');
    }
    public function destroyLesson(Lesson $lesson)
    {
        if (auth()->user()->role === 'instructor' && $lesson->section->course->instructor_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بحذف هذا الدرس.');
        }

        if ($lesson->video_url && Storage::disk('public')->exists($lesson->video_url)) {
            Storage::disk('public')->delete($lesson->video_url);
        }

        $courseId = $lesson->section->course_id;
        $lesson->delete();

        return back()->with('success', 'تم حذف الدرس بنجاح.');
    }
}
