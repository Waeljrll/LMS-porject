<?php

namespace App\Livewire\Forms;

use App\Models\QuizQuestion;
use Livewire\Form;

class QuestionForm extends Form
{
    public ?QuizQuestion $questionModel = null;

    public $question_text = '';
    public $question_type = 'mcq';
    public $points = 1;
    public $explanation = '';

    public $options = [
        ['option_text' => '', 'is_correct' => false],
        ['option_text' => '', 'is_correct' => false],
    ];

    public function setQuestion(QuizQuestion $question): void
    {
        $this->questionModel = $question;
        $this->question_text = $question->question_text;
        $this->question_type = $question->question_type;
        $this->points = $question->points;
        $this->explanation = $question->explanation;

        $this->options = $question->options->map(function ($option) {
            return [
                'id' => $option->id,
                'option_text' => $option->option_text,
                'is_correct' => (bool) $option->is_correct
            ];
        })->toArray();
    }

    public function resetForm(): void
    {
        $this->questionModel = null;
        $this->question_text = '';
        $this->question_type = 'mcq';
        $this->points = 1;
        $this->explanation = '';
        $this->options = [
            ['option_text' => '', 'is_correct' => false],
            ['option_text' => '', 'is_correct' => false],
        ];
    }

    public function rules(): array
    {
        $rules = [
            'question_text' => 'required|string|min:5',
            'question_type' => 'required|in:mcq,true_false',
            'points' => 'required|integer|min:1|max:10',
            'explanation' => 'nullable|string',
        ];

        if ($this->question_type === 'mcq') {
            $rules['options'] = 'required|array|min:2|max:6';
            $rules['options.*.option_text'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function validationAttributes(): array
    {
        return [
            'question_text' => 'نص السؤال',
            'points' => 'النقاط',
            'options.*.option_text' => 'نص الاختيار',
        ];
    }

    public function messages(): array
    {
        return [
            'question_text.required' => 'نص السؤال مطلوب.',
            'question_text.min' => 'نص السؤال يجب أن يكون 5 أحرف على الأقل.',
            'points.required' => 'النقاط مطلوبة.',
            'points.min' => 'النقاط يجب أن تكون 1 على الأقل.',
            'options.min' => 'يجب إضافة خيارين على الأقل.',
            'options.max' => 'لا يمكن إضافة أكثر من 6 خيارات.',
            'options.*.option_text.required' => 'نص الخيار مطلوب.',
        ];
    }
}
