<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\CourseForm;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class CourseWizard extends Component
{
    use WithFileUploads;

    public CourseForm $form;
    public $currentStep = 1;
    public $courseId;

    public function mount($id = null)
    {
        if ($id) {
            $this->courseId = $id;
            $course = Course::with('objectives')->findOrFail($id);
            $this->form->setCourse($course);
        } else {
            if (Auth::user()->isInstructor()) {
                $this->form->instructor_id = Auth::id();
            }
        }
    }

    public function nextStep()
    {
        $this->validate($this->form->getStep1Rules());
        $this->currentStep = 2;
    }

    public function previousStep()
    {
        $this->currentStep = 1;
    }

    public function addObjective()
    {
        $this->form->objectives[] = '';
    }

    public function removeObjective($index)
    {
        if (count($this->form->objectives) > 3) {
            unset($this->form->objectives[$index]);
            $this->form->objectives = array_values($this->form->objectives);
        }
    }

    public function saveCourse()
    {
        $this->validate($this->form->getAllRules());

        // Authorization check for instructors
        if ($this->courseId && Auth::user()->isInstructor()) {
            $existingCourse = Course::find($this->courseId);
            if ($existingCourse && $existingCourse->instructor_id !== Auth::id()) {
                abort(403, 'You can only edit your own courses.');
            }
        }

        // Prevent publishing without lessons
        if ($this->form->status === 'published') {
            if ($this->courseId) {
                $course = Course::find($this->courseId);
                if ($course->lessons()->count() === 0) {
                    session()->flash('error', 'عذراً! لا يمكن نشر الكورس قبل إضافة درس واحد على الأقل. تم حفظ الكورس كـ مسودة (Draft).');
                    $this->form->status = 'draft';
                }
            } else {
                session()->flash('error', 'لا يمكن نشر كورس جديد مباشرة قبل إضافة محتوى. تم تحويل الحالة إلى مسودة (Draft).');
                $this->form->status = 'draft';
            }
        }

        try {
            DB::beginTransaction();

            if ($this->courseId) {
                $course = Course::findOrFail($this->courseId);
                $this->form->update($course);
                $message = 'Course updated successfully!';
                $savedCourseId = $this->courseId;
            } else {
                if (Auth::user()->isInstructor()) {
                    $this->form->instructor_id = Auth::id();
                }

                $course = $this->form->store();
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

    public function render()
    {
        return view('livewire.admin.course-wizard', [
            'categories' => Category::all(),
            'instructors' => User::where('role', 'instructor')->get(),
        ]);
    }
}
