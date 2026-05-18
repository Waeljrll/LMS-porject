<?php

namespace App\Livewire\Admin;

use App\Http\Requests\CourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CourseWizard extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $courseId;

    public $title, $short_description, $description, $category_id, $difficulty_level, $language = 'English', $thumbnail, $status = 'draft';
    public $instructor_id;
    public $existingThumbnail;

    public $requirements, $who_is_it_for, $duration_hours = 0, $duration_minutes = 0;
    public $price = 0;
    public $objectives = ['', '', ''];

    public function mount($id = null)
    {
        if ($id) {
            $this->courseId = $id;
            $course = Course::with('objectives')->findOrFail($id);

            $this->title = $course->title;
            $this->short_description = $course->short_description;
            $this->description = $course->description;
            $this->category_id = $course->category_id;
            $this->instructor_id = $course->instructor_id;
            $this->difficulty_level = $course->difficulty_level;
            $this->language = $course->language ?? 'English';
            $this->existingThumbnail = $course->thumbnail;
            $this->thumbnail = null;
            $this->status = $course->status ?? 'draft';
            $this->requirements = $course->requirements ?? '';
            $this->who_is_it_for = $course->who_is_it_for ?? '';
            $this->duration_hours = $course->duration_hours ?? 0;
            $this->duration_minutes = $course->duration_minutes ?? 0;
            $this->price = $course->price ?? 0;

            $objectives = $course->objectives->pluck('objective')->toArray();
            $this->objectives = !empty($objectives) ? $objectives : ['', '', ''];
        } else {
            if (Auth::user()->isInstructor()) {
                $this->instructor_id = Auth::id();
            }
        }
    }

    protected function getStep1Rules(): array
    {
        $request = new CourseRequest();
        $allRules = $request->rules($this->courseId);

        return array_intersect_key($allRules, array_flip([
            'title',
            'short_description',
            'description',
            'category_id',
            'instructor_id',
            'difficulty_level',
            'language',
            'thumbnail',
            'status'
        ]));
    }

    public function nextStep()
    {
        $this->validate($this->getStep1Rules());
        $this->currentStep = 2;
    }

    public function previousStep()
    {
        $this->currentStep = 1;
    }

    public function addObjective()
    {
        $this->objectives[] = '';
    }

    public function removeObjective($index)
    {
        if (count($this->objectives) > 3) {
            unset($this->objectives[$index]);
            $this->objectives = array_values($this->objectives);
        }
    }


    public function saveCourse()
    {
        $courseRequest = new CourseRequest();
        $this->validate($courseRequest->rules($this->courseId));
        if ($this->courseId && Auth::user()->isInstructor()) {
            $existingCourse = Course::find($this->courseId);
            if ($existingCourse && $existingCourse->instructor_id !== Auth::id()) {
                abort(403, 'You can only edit your own courses.');
            }
        }

        if ($this->status === 'published') {
            if ($this->courseId) {
                $course = Course::find($this->courseId);

                // Check if course has at least 1 lesson
                $lessonsCount = $course->lessons()->count();

                if ($lessonsCount === 0) {
                    session()->flash('error', 'عذراً! لا يمكن نشر الكورس قبل إضافة درس واحد على الأقل. تم حفظ الكورس كـ مسودة (Draft).');
                    $this->status = 'draft';
                }
            } else {
                // New course - cannot publish immediately
                session()->flash('error', 'لا يمكن نشر كورس جديد مباشرة قبل إضافة محتوى. تم تحويل الحالة إلى مسودة (Draft).');
                $this->status = 'draft';
            }
        }

        try {
            DB::beginTransaction();

            $thumbnailPath = $this->handleThumbnail();

            if ($this->courseId) {
                $this->updateCourse($thumbnailPath);
                $message = 'Course updated successfully!';
                $savedCourseId = $this->courseId;
            } else {
                if (Auth::user()->isInstructor()) {
                    $this->instructor_id = Auth::id();
                }

                $course = Course::create($this->mapCourseData($thumbnailPath));
                $this->saveObjectives($course);
                $savedCourseId = $course->id;
                $message = 'Course created successfully!';
            }

            DB::commit();
            session()->flash('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error: ' . $e->getMessage());
            return;
        }

        if (Auth::user()->isAdmin()) {
            return redirect()->to(route('admin.courses.content', $savedCourseId));
        } else {
            return redirect()->to(route('instructor.courses.content', $savedCourseId));
        }
    }


    protected function createCourse($thumbnailPath)
    {
        $course = Course::create($this->mapCourseData($thumbnailPath));
        $this->saveObjectives($course);
    }

    protected function updateCourse($thumbnailPath)
    {
        $course = Course::findOrFail($this->courseId);
        $finalThumbnail = $thumbnailPath ?: $this->existingThumbnail;
        $course->update($this->mapCourseData($finalThumbnail));
        $course->objectives()->delete();
        $this->saveObjectives($course);
    }

    protected function mapCourseData($thumbnail)
    {
        return [
            'category_id'       => $this->category_id,
            'instructor_id'     => $this->instructor_id,
            'title'             => $this->title,
            'slug'              => Str::slug($this->title),
            'short_description' => $this->short_description,
            'description'       => $this->description,
            'difficulty_level'  => $this->difficulty_level,
            'language'          => $this->language,
            'price'             => $this->price,
            'status'            => $this->status,
            'thumbnail'         => $thumbnail,
            'requirements'      => $this->requirements,
            'who_is_it_for'     => $this->who_is_it_for,
            'duration_hours'    => $this->duration_hours,
            'duration_minutes'  => $this->duration_minutes,
        ];
    }

    protected function saveObjectives($course)
    {
        foreach (array_filter($this->objectives) as $objectiveText) {
            if (!empty($objectiveText)) {
                $course->objectives()->create(['objective' => $objectiveText]);
            }
        }
    }

    protected function handleThumbnail()
    {
        if ($this->thumbnail) {
            if ($this->courseId && $this->existingThumbnail && Storage::disk('public')->exists($this->existingThumbnail)) {
                Storage::disk('public')->delete($this->existingThumbnail);
            }
            return $this->thumbnail->store('courses', 'public');
        }
        return null;
    }

    public function render()
    {
        return view('livewire.admin.course-wizard', [
            'categories' => Category::all(),
            'instructors' => User::where('role', 'instructor')->get(),
        ]);
    }
}
