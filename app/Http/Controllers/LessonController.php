<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function storeLesson(UpdateLessonRequest $request, $section_id)
    {
        $section = Section::findOrFail($section_id);

        if (auth()->user()->role === 'instructor' && $section->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();
        $nextOrder = $section->lessons()->count() + 1;

        $createData = [
            'section_id' => $section->id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'duration_minutes' => $validated['duration_minutes'],
            'lesson_type' => $validated['lesson_type'],
            'order_number' => $nextOrder,
            'is_preview' => isset($validated['is_preview']) ? (bool)$validated['is_preview'] : false,
        ];

        if ($validated['lesson_type'] === 'document') {
            $createData['text_content'] = $request->input('text_content');
            $createData['video_url'] = null;
        } else {
            $createData['text_content'] = null;

            // تعديل المسار ليرمي فوراً جوه فولدر lessons اللي أنت عملته
            if ($request->hasFile('video_file')) {
                // التخزين هنا هيعمل ملفات جوه public/lessons علطول
                $createData['video_url'] = $request->file('video_file')->store('lessons', 'public');
            } else {
                $createData['video_url'] = $validated['video_url'] ?? null;
            }
        }

        Lesson::create($createData);

        return redirect()
            ->route(auth()->user()->role . '.courses.content', $section->course_id)
            ->with('success', 'تم إضافة الدرس الجديد بنجاح وجاري رفع الفيديو.');
    }
    public function editLesson(Lesson $lesson)
    {
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

        $updateData = [
            'title' => $validated['title'],
            'duration_minutes' => $validated['duration_minutes'],
            'lesson_type' => $validated['lesson_type'],
            'is_preview' => isset($validated['is_preview']) ? (bool)$validated['is_preview'] : false,
        ];

        if ($validated['lesson_type'] === 'document') {
            $updateData['text_content'] = $request->input('text_content');
            if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($lesson->video_url);
            }
            $updateData['video_url'] = null;
        } else {
            $updateData['text_content'] = null;

            if ($request->hasFile('video_file')) {
                if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($lesson->video_url);
                }
                $updateData['video_url'] = $request->file('video_file')->store('lessons', 'public');
            } elseif (!empty($validated['video_url'])) {
                if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($lesson->video_url);
                }
                $updateData['video_url'] = $validated['video_url'];
            } else {
                $updateData['video_url'] = $lesson->video_url;
            }
        }

        $lesson->update($updateData);

        return redirect()
            ->route(auth()->user()->role . '.courses.content', $lesson->section->course_id)
            ->with('success', 'تم تحديث الدرس ومحتواه بنجاح.');
    }
    public function destroyLesson(Lesson $lesson)
    {
        if (auth()->user()->role === 'instructor' && $lesson->section->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $courseId = $lesson->section->course_id;

        if ($lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($lesson->video_url);
        }

        $lesson->delete();

        return redirect()
            ->route(auth()->user()->role . '.courses.content', $courseId)
            ->with('success', 'تم حذف الدرس بنجاح.');
    }
}
