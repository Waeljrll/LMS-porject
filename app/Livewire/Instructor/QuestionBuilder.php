<?php

namespace App\Livewire\Instructor;

use App\Livewire\Forms\QuestionForm;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class QuestionBuilder extends Component
{
    use WithFileUploads;

    public Quiz $quiz;
    public QuestionForm $form;
    public bool $isEditing = false;
    public bool $hasAttempts = false;

    public $time_limit_minutes;
    public $passing_score_percentage;
    public $max_attempts;

    public function mount(Quiz $quiz): void
    {
        Gate::authorize('update', $quiz);
        $this->hasAttempts = $quiz->attempts()->exists();

        $this->quiz = $quiz;

        $this->quiz = $quiz;

        $this->time_limit_minutes = $quiz->time_limit_minutes;
        $this->passing_score_percentage = $quiz->passing_score_percentage;
        $this->max_attempts = $quiz->max_attempts;
    }

    public function updatedFormQuestionType($value): void
    {
        if ($value === 'true_false') {
            $this->form->options = [
                ['option_text' => 'True', 'is_correct' => true],
                ['option_text' => 'False', 'is_correct' => false],
            ];
            return;
        }

        if ($value === 'essay') {
            $this->form->options = [];
            return;
        }

        $this->form->options = [
            ['option_text' => '', 'is_correct' => false],
            ['option_text' => '', 'is_correct' => false],
        ];
    }

    public function addOption(): void
    {
        if ($this->form->question_type === 'essay') {
            return;
        }

        if (count($this->form->options) < 6) {
            $this->form->options[] = ['option_text' => '', 'is_correct' => false];
        }
    }

    public function removeOption(int $index): void
    {
        if ($this->form->question_type !== 'mcq') {
            return;
        }

        if (count($this->form->options) > 2) {
            unset($this->form->options[$index]);
            $this->form->options = array_values($this->form->options);
        }
    }

    public function setCorrectOption(int $selectedIndex): void
    {
        foreach ($this->form->options as $index => $option) {
            $this->form->options[$index]['is_correct'] = ($index === $selectedIndex);
        }
    }

    public function save(): void
    {


        if ($this->hasAttempts) {
            session()->flash('error', 'لا يمكن تعديل الأسئلة؛ لأن هناك طلاباً بدأوا بالفعل في حل هذا الاختبار.');
            return;
        }
        if (
            $this->form->question_type !== 'essay'
            && ! collect($this->form->options)->contains('is_correct', true)
        ) {
            $this->addError('form.options', 'الرجاء اختيار إجابة صحيحة واحدة على الأقل لهذا السؤال.');
            return;
        }

        $this->form->validate();

        // 2. رفع الصورة الجديدة خارج الـ Transaction (لأن Storage I/O ليس Transactional)
        $newImagePath = null;
        if ($this->form->image) {
            $newImagePath = $this->form->image->store('questions', 'public');
        }

        // تحديد المسار النهائي الذي سيتم حفظه في قاعدة البيانات
        $finalImagePath = $newImagePath ?? $this->form->existing_image_path;

        try {
            // بدء المعاملة التبادلية
            DB::transaction(function () use ($finalImagePath) {
                if ($this->isEditing && $this->form->questionModel) {
                    // كود التعديل (كما هو لا يحتاج لتغيير هنا)
                    $question = $this->form->questionModel;
                    $question->update([
                        'question_text' => $this->form->question_text,
                        'question_type' => $this->form->question_type,
                        'points'        => $this->form->points,
                        'explanation'   => $this->form->explanation,
                        'image_path'    => $finalImagePath,
                    ]);
                    $question->options()->delete();
                } else {

                    $quizLock = Quiz::lockForUpdate()->find($this->quiz->id);

                    $nextOrder = ($quizLock->questions()->max('sort_order') ?? 0) + 1;

                    // 3. إنشاء السؤال بالترتيب المحمي والجديد
                    $question = $quizLock->questions()->create([
                        'question_text' => $this->form->question_text,
                        'question_type' => $this->form->question_type,
                        'points'        => $this->form->points,
                        'explanation'   => $this->form->explanation,
                        'image_path'    => $finalImagePath,
                        'sort_order'    => $nextOrder,
                    ]);
                }

                // حفظ الاختيارات ...
                if ($this->form->question_type !== 'essay') {
                    foreach ($this->form->options as $index => $option) {
                        $question->options()->create([
                            'option_text' => $option['option_text'],
                            'is_correct'  => $option['is_correct'],
                            'sort_order'  => $index + 1,
                        ]);
                    }
                }
            });

            if ($newImagePath && $this->form->existing_image_path) {
                Storage::disk('public')->delete($this->form->existing_image_path);
            }
        } catch (\Throwable $e) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            logger()->error('فشل حفظ السؤال: ' . $e->getMessage());
            session()->flash('error', 'حدث خطأ غير متوقع أثناء الحفظ، يرجى المحاولة مرة أخرى.');
            return;
        }

        $this->resetForm();
        session()->flash('success', 'تم حفظ السؤال بنجاح.');
    }

    public function editQuestion(QuizQuestion $question): void
    {
        $this->isEditing = true;
        $this->form->setQuestion($question->load('options'));
    }

    public function deleteQuestion(int $id): void
    {
        // 1. جلب السؤال والتأكد من ملكيته وأنه ينتمي لهذا الاختبار لحماية السيرفر من الـ ID Manipulation
        $question = $this->quiz->questions()->findOrFail($id);

        $imageToDelete = $question->image_path;

        try {
            DB::transaction(function () use ($question) {
                // حذف الخيارات أولاً (لو لم تكن مبرمجة على Cascade Delete في قاعدة البيانات)
                $question->options()->delete();

                // حذف السؤال نفسه
                $question->delete();

                // إعادة ترتيب الأسئلة المتبقية تلقائياً للحفاظ على تسلسل الـ sort_order نظيفاً
                $this->quiz->questions()
                    ->where('sort_order', '>', $question->sort_order)
                    ->decrement('sort_order');
            });

            // 2. لا نحذف الملف من السيرفر إلا بعد نجاح الترانزاكشن تماماً في قاعدة البيانات
            if ($imageToDelete) {
                Storage::disk('public')->delete($imageToDelete);
            }

            session()->flash('success', 'تم حذف السؤال وإعادة ترتيب الأسئلة بنجاح.');
        } catch (\Throwable $e) {
            session()->flash('error', 'فشلت عملية الحذف، يرجى المحاولة مرة أخرى.');
        }
    }

    public function moveUp(QuizQuestion $question): void
    {
        $previousQuestion = $this->quiz->questions()
            ->where('sort_order', '<', $question->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previousQuestion) {
            $this->swapOrder($question, $previousQuestion);
        }
    }

    public function moveDown(QuizQuestion $question): void
    {
        $nextQuestion = $this->quiz->questions()
            ->where('sort_order', '>', $question->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($nextQuestion) {
            $this->swapOrder($question, $nextQuestion);
        }
    }

    private function swapOrder(QuizQuestion $q1, QuizQuestion $q2): void
    {
        $tempOrder = $q1->sort_order;
        $q1->update(['sort_order' => $q2->sort_order]);
        $q2->update(['sort_order' => $tempOrder]);
    }

    private function reorderQuestionsSequentially(): void
    {
        $questions = $this->quiz->questions()->orderBy('sort_order')->get();
        foreach ($questions as $index => $question) {
            $question->update(['sort_order' => $index + 1]);
        }
    }

    public function resetForm(): void
    {
        $this->form->resetForm();
        $this->isEditing = false;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.instructor.question-builder', [
            'questions' => $this->quiz->questions()->with('options')->orderBy('sort_order')->get(),
        ]);
    }
}
