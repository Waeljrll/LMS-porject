<?php

namespace App\Livewire\Student;

use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class QuizTaker extends Component
{
    public Quiz $quiz;
    public array $questions = [];
    public array $answers = [];
    public array $essayAnswers = [];
    public ?QuizAttempt $currentAttempt = null;
    public int $currentQuestionIndex = 0;
    public bool $quizSubmitted = false;
    public array $results = [];
    public ?int $timeRemaining = null;
    public bool $isTimeUp = false;

    public function mount(Quiz $quiz): void
    {
        $this->quiz = $quiz;

        $this->quiz->load(['section.course']);

        $course = $this->quiz->section->course;
        $student = Auth::user();

        $isEnrolled = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'يجب الاشتراك في الكورس أولاً لتتمكن من حل هذا الاختبار.');
        }

        if (!$this->quiz->canAttempt($student)) {
            session()->flash('error', 'لقد استنفذت جميع المحاولات المسموحة لهذا الاختبار.');
            $this->redirect(route('student.enrollments.index'));
            return;
        }

        $existingAttempt = $student->quizAttempts()
            ->where('quiz_id', $this->quiz->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existingAttempt) {
            $this->currentAttempt = $existingAttempt;
            $this->loadExistingAnswers();
        } else {
            $this->currentAttempt = QuizAttempt::create([
                'quiz_id' => $this->quiz->id,
                'student_id' => $student->id,
                'attempt_number' => $this->getNextAttemptNumber(),
                'started_at' => now(),
                'status' => 'in_progress',
                'ip_address' => request()->ip(),
            ]);
        }

        $questionsQuery = $this->quiz->questions()
            ->with('options')
            ->orderBy('sort_order');

        if ($this->quiz->shuffle_questions) {
            $questionsQuery = $questionsQuery->inRandomOrder();
        }

        $this->questions = $questionsQuery->get()->toArray();

        foreach ($this->questions as $question) {
            $this->answers[$question['id']] = null;
            if ($question['question_type'] === 'essay') {
                $this->essayAnswers[$question['id']] = '';
            }
        }

        if ($this->quiz->time_limit_minutes) {
            $this->timeRemaining = $this->currentAttempt->timeRemaining();
        }
    }

    private function getNextAttemptNumber(): int
    {
        return QuizAttempt::where('quiz_id', $this->quiz->id)
            ->where('student_id', Auth::id())
            ->count() + 1;
    }

    private function loadExistingAnswers(): void
    {
        $savedAnswers = $this->currentAttempt->answers()
            ->with('question')
            ->get();

        foreach ($savedAnswers as $answer) {
            if ($answer->question->question_type === 'essay') {
                $this->essayAnswers[$answer->question_id] = $answer->answer_text ?? '';
            } else {
                $this->answers[$answer->question_id] = $answer->selected_option_id;
            }
        }
    }

    public function getCurrentQuestionProperty(): array
    {
        return $this->questions[$this->currentQuestionIndex] ?? [];
    }

    public function getTotalQuestionsProperty(): int
    {
        return count($this->questions);
    }

    public function getProgressPercentageProperty(): int
    {
        if (empty($this->questions)) return 0;
        return (int) ((($this->currentQuestionIndex + 1) / count($this->questions)) * 100);
    }

    public function getAnsweredCountProperty(): int
    {
        $answered = 0;
        foreach ($this->questions as $q) {
            if ($q['question_type'] === 'essay') {
                if (!empty(trim($this->essayAnswers[$q['id']] ?? ''))) $answered++;
            } else {
                if (!empty($this->answers[$q['id']])) $answered++;
            }
        }
        return $answered;
    }

    public function updateTimer(): void
    {
        if ($this->quiz->time_limit_minutes && $this->currentAttempt) {
            $this->timeRemaining = $this->currentAttempt->timeRemaining();

            if ($this->timeRemaining !== null && $this->timeRemaining <= 0 && !$this->isTimeUp) {
                $this->isTimeUp = true;
                $this->submitQuiz();
            }
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function selectOption(int $questionId, int $optionId): void
    {
        $this->answers[$questionId] = $optionId;
        $this->saveAnswerToAttempt($questionId, $optionId);
    }

    public function updatedEssayAnswers($value, $key): void
    {
        $questionId = (int) $key;
        \App\Models\QuizAnswer::updateOrCreate(
            [
                'attempt_id' => $this->currentAttempt->id,
                'question_id' => $questionId,
            ],
            [
                'answer_text' => $value,
                'is_correct' => null,
                'points_earned' => 0,
            ]
        );
    }

    private function saveAnswerToAttempt(int $questionId, ?int $optionId): void
    {
        if (!$this->currentAttempt) return;

        $question = collect($this->questions)->firstWhere('id', $questionId);
        if (!$question) return;

        $isCorrect = false;
        $pointsEarned = 0;

        if ($question['question_type'] !== 'essay' && $optionId) {
            $option = collect($question['options'])->firstWhere('id', $optionId);
            $isCorrect = $option['is_correct'] ?? false;
            $pointsEarned = $isCorrect ? ($question['points'] ?? 1) : 0;
        }

        \App\Models\QuizAnswer::updateOrCreate(
            [
                'attempt_id' => $this->currentAttempt->id,
                'question_id' => $questionId,
            ],
            [
                'selected_option_id' => $optionId,
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
            ]
        );
    }

    public function submitQuiz(): void
    {
        if ($this->quizSubmitted) return;

        foreach ($this->questions as $question) {
            if ($question['question_type'] === 'essay') {
                $text = $this->essayAnswers[$question['id']] ?? '';
                \App\Models\QuizAnswer::updateOrCreate(
                    [
                        'attempt_id' => $this->currentAttempt->id,
                        'question_id' => $question['id'],
                    ],
                    [
                        'answer_text' => $text,
                        'is_correct' => null,
                        'points_earned' => 0,
                    ]
                );
            }
        }

        $totalPoints = collect($this->questions)->sum('points');
        $earnedPoints = \App\Models\QuizAnswer::where('attempt_id', $this->currentAttempt->id)->sum('points_earned');

        $percentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;
        $passingScore = $this->quiz->passing_score_percentage;

        $status = match (true) {
            $percentage >= $passingScore => 'passed',
            default => 'failed',
        };

        $hasEssay = collect($this->questions)->contains('question_type', 'essay');
        if ($hasEssay) {
            $status = 'submitted';
        }

        $this->currentAttempt->update([
            'submitted_at' => now(),
            'time_taken_seconds' => $this->currentAttempt->started_at->diffInSeconds(now()),
            'score' => $earnedPoints,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'status' => $status,
        ]);

        $this->quizSubmitted = true;
        $this->results = [
            'score' => $earnedPoints,
            'total' => $totalPoints,
            'percentage' => $percentage,
            'status' => $status,
            'passed' => $status === 'passed',
            'requiresGrading' => $hasEssay,
        ];

        session()->flash('success', $status === 'passed' ? 'تهانينا! لقد نجحت في الاختبار.' : 'تم تسليم الاختبار بنجاح.');
    }

    public function render()
    {
        return view('livewire.student.quiz-taker', [
            'currentQuestion' => $this->currentQuestion,
            'totalQuestions'  => $this->totalQuestions,
            'progressPercentage' => $this->progressPercentage,
            'answeredCount'   => $this->answeredCount,
        ]);
    }
}
