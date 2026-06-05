<?php

namespace App\Livewire\Instructor;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Livewire\Forms\QuestionForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QuestionBuilder extends Component
{
    public Quiz $quiz;
    public QuestionForm $form;
    public bool $isEditing = false;

    public function mount(Quiz $quiz): void
    {
        // Authorization
        if (Auth::user()->isInstructor() && $quiz->section->course->instructor_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الامتحان.');
        }

        $this->quiz = $quiz;
    }

    public function updatedFormQuestionType($value): void
    {
        if ($value === 'true_false') {
            $this->form->options = [
                ['option_text' => 'True', 'is_correct' => true],
                ['option_text' => 'False', 'is_correct' => false],
            ];
        } else {
            $this->form->options = [
                ['option_text' => '', 'is_correct' => false],
                ['option_text' => '', 'is_correct' => false],
            ];
        }
    }

    public function addOption(): void
    {
        if (count($this->form->options) < 6) {
            $this->form->options[] = ['option_text' => '', 'is_correct' => false];
        }
    }

    public function removeOption(int $index): void
    {
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
        $hasCorrect = collect($this->form->options)->contains('is_correct', true);
        if (!$hasCorrect) {
            $this->addError('form.options', 'يجب تحديد إجابة واحدة كإجابة صحيحة.');
            return;
        }

        $this->validate($this->form->rules());

        DB::transaction(function () {
            if ($this->isEditing && $this->form->questionModel) {
                $question = $this->form->questionModel;
                $question->update([
                    'question_text' => $this->form->question_text,
                    'question_type' => $this->form->question_type,
                    'points' => $this->form->points,
                    'explanation' => $this->form->explanation,
                ]);
                $question->options()->delete();
            } else {
                $nextOrder = $this->quiz->questions()->max('sort_order') + 1;
                $question = $this->quiz->questions()->create([
                    'question_text' => $this->form->question_text,
                    'question_type' => $this->form->question_type,
                    'points' => $this->form->points,
                    'explanation' => $this->form->explanation,
                    'sort_order' => $nextOrder
                ]);
            }

            foreach ($this->form->options as $option) {
                $question->options()->create([
                    'option_text' => $option['option_text'],
                    'is_correct' => $option['is_correct'],
                ]);
            }
        });

        $this->resetForm();
        session()->flash('success', 'تم حفظ السؤال بنجاح.');
    }

    public function editQuestion(QuizQuestion $question): void
    {
        $this->isEditing = true;
        $this->form->setQuestion($question);
    }

    public function deleteQuestion(QuizQuestion $question): void
    {
        $question->delete();
        $this->reorderQuestionsSequentially();
        session()->flash('success', 'تم حذف السؤال.');
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
            'questions' => $this->quiz->questions()->with('options')->orderBy('sort_order')->get()
        ]);
    }
}
