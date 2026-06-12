@php
    // Ensure all needed variables are available
    $currentQuestion = $currentQuestion ?? [];
    $totalQuestions = $totalQuestions ?? 0;
    $progressPercentage = $progressPercentage ?? 0;
    $answeredCount = $answeredCount ?? 0;
    $quizSubmitted = $quizSubmitted ?? false;
    $results = $results ?? [];
    $timeRemaining = $timeRemaining ?? null;
    $currentQuestionIndex = $currentQuestionIndex ?? 0;
    $questions = $questions ?? [];
    $answers = $answers ?? [];
    $essayAnswers = $essayAnswers ?? [];
@endphp

<div class="quiz-container">
    {{-- Header / Timer Bar --}}
    <div class="bg-white shadow-sm border-bottom sticky-top" style="z-index: 1020;">
        <div class="container py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold">{{ $quiz->title }}</h4>
                    <small class="text-muted">
                        <i class="bi bi-book me-1"></i> {{ $totalQuestions }} أسئلة |
                        <i class="bi bi-star me-1"></i> {{ $quiz->totalPoints() }} نقطة
                    </small>
                </div>

                @if ($quiz->time_limit_minutes)
                    <div
                        class="quiz-timer {{ ($timeRemaining ?? 999) <= 60 ? 'text-danger fw-bold' : (($timeRemaining ?? 999) <= 300 ? 'text-warning fw-bold' : 'text-secondary') }}">
                        <i class="bi bi-clock me-1"></i>
                        <span wire:poll.1s="updateTimer">
                            {{ $currentAttempt ? $currentAttempt->timeRemainingFormatted() : '--:--' }}
                        </span>
                    </div>
                @endif
            </div>

            <div class="progress mt-3" style="height: 8px;">
                <div class="progress-bar bg-primary" role="progressbar"
                    style="width: {{ $progressPercentage }}%; transition: width 0.3s ease;"></div>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <small class="text-muted">السؤال {{ $currentQuestionIndex + 1 }} من {{ $totalQuestions }}</small>
                <small class="text-muted">{{ $answeredCount }} تم الإجابة</small>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-4">

            {{-- Main Question Area --}}
            <div class="col-lg-8">

                @if ($quizSubmitted)
                    {{-- Results View --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center p-5">
                            @if ($results['passed'] ?? false)
                                <i class="bi bi-trophy-fill text-success display-1 mb-3"></i>
                                <h2 class="text-success mb-3">تهانينا! لقد نجحت</h2>
                            @elseif($results['requiresGrading'] ?? false)
                                <i class="bi bi-hourglass-split text-warning display-1 mb-3"></i>
                                <h2 class="text-warning mb-3">بانتظار التصحيح</h2>
                            @else
                                <i class="bi bi-x-circle-fill text-danger display-1 mb-3"></i>
                                <h2 class="text-danger mb-3">لم تنجح هذه المرة</h2>
                            @endif

                            <div class="row justify-content-center g-3 mt-4">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="mb-0">{{ $results['score'] ?? 0 }}</h3>
                                            <small class="text-muted">النقاط المكتسبة</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="mb-0">{{ $results['percentage'] ?? 0 }}%</h3>
                                            <small class="text-muted">النسبة المئوية</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="mb-0">{{ $results['total'] ?? 0 }}</h3>
                                            <small class="text-muted">إجمالي النقاط</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('student.courses.learn', $quiz->section->course) }}"
                                class="btn btn-primary mt-4">
                                <i class="bi bi-arrow-left me-1"></i> العودة للكورس
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Active Question --}}
                    @if (!empty($currentQuestion))
                        <div class="card shadow-sm border-0" wire:key="question-card-{{ $currentQuestionIndex }}">
                            <div class="card-body p-4">

                                {{-- Question Header --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-primary fs-6">س{{ $currentQuestionIndex + 1 }}</span>
                                    <span class="badge bg-warning text-dark">{{ $currentQuestion['points'] ?? 1 }}
                                        نقطة</span>
                                </div>

                                {{-- Question Text --}}
                                <div class="mb-4">
                                    <h5 class="fw-bold lh-lg">{!! $currentQuestion['question_text'] ?? '' !!}</h5>
                                </div>

                                {{-- Question Image --}}
                                @if (!empty($currentQuestion['image_path']))
                                    <div class="mb-4 text-center">
                                        <img src="{{ Storage::url($currentQuestion['image_path']) }}"
                                            class="img-fluid rounded border" style="max-height: 300px;"
                                            alt="صورة السؤال">
                                    </div>
                                @endif

                                {{-- MCQ Options --}}
                                @if (($currentQuestion['question_type'] ?? 'mcq') === 'mcq')
                                    <div class="list-group gap-2">
                                        @foreach ($currentQuestion['options'] ?? [] as $option)
                                            @php
                                                $isSelected =
                                                    ($answers[$currentQuestion['id']] ?? null) == $option['id'];
                                            @endphp
                                            <button type="button"
                                                class="quiz-option list-group-item p-3 rounded d-flex align-items-center gap-3 text-start {{ $isSelected ? 'bg-primary text-white border-primary' : 'border' }}"
                                                wire:key="mcq-opt-{{ $currentQuestion['id'] }}-{{ $option['id'] }}"
                                                wire:click="selectOption({{ $currentQuestion['id'] }}, {{ $option['id'] }})">
                                                <div class="form-check m-0">
                                                    <input class="form-check-input" type="radio"
                                                        {{ $isSelected ? 'checked' : '' }} disabled
                                                        style="pointer-events: none;">
                                                </div>
                                                <span class="flex-grow-1">{{ $option['option_text'] }}</span>
                                                @if ($isSelected)
                                                    <i class="bi bi-check-circle-fill"></i>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>

                                    {{-- True/False --}}
                                @elseif(($currentQuestion['question_type'] ?? '') === 'true_false')
                                    <div class="row g-3">
                                        @foreach ($currentQuestion['options'] ?? [] as $option)
                                            @php
                                                $isSelected =
                                                    ($answers[$currentQuestion['id']] ?? null) == $option['id'];
                                                $isTrue =
                                                    strtolower($option['option_text']) === 'true' ||
                                                    $option['option_text'] === 'صح';
                                            @endphp
                                            <div class="col-6"
                                                wire:key="tf-opt-{{ $currentQuestion['id'] }}-{{ $option['id'] }}">
                                                <button type="button"
                                                    class="card text-center py-4 border-2 h-100 w-100 {{ $isSelected ? ($isTrue ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10') : 'border-light' }}"
                                                    wire:click="selectOption({{ $currentQuestion['id'] }}, {{ $option['id'] }})">
                                                    @if ($isTrue)
                                                        <i class="bi bi-check-circle-fill text-success fs-1 mb-2"></i>
                                                        <span class="fw-bold text-success">صح (True)</span>
                                                    @else
                                                        <i class="bi bi-x-circle-fill text-danger fs-1 mb-2"></i>
                                                        <span class="fw-bold text-danger">خطأ (False)</span>
                                                    @endif
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Essay --}}
                                @elseif(($currentQuestion['question_type'] ?? '') === 'essay')
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">أكتب إجابتك هنا:</label>
                                        <textarea class="form-control" rows="6"
                                            wire:model.live.debounce.500ms="essayAnswers.{{ $currentQuestion['id'] }}" placeholder="اكتب إجابتك بالتفصيل..."></textarea>
                                        <div class="form-text">سيتم تصحيح هذا السؤال يدوياً من قبل المحاضر.</div>
                                    </div>
                                @endif

                            </div>

                            {{-- Navigation Footer --}}
                            <div class="card-footer bg-white border-top d-flex justify-content-between p-3">
                                <button type="button" class="btn btn-outline-secondary" wire:click="previousQuestion"
                                    @disabled($currentQuestionIndex === 0)>
                                    <span wire:loading.remove wire:target="previousQuestion">
                                        <i class="bi bi-arrow-right me-1"></i> السابق
                                    </span>
                                    <span wire:loading wire:target="previousQuestion">
                                        <span class="spinner-border spinner-border-sm"></span>
                                    </span>
                                </button>

                                @if ($currentQuestionIndex === $totalQuestions - 1)
                                    <button type="button" class="btn btn-success" wire:click="submitQuiz"
                                        wire:confirm="هل أنت متأكد من تسليم الاختبار؟ لا يمكن التراجع بعد التسليم.">
                                        <span wire:loading.remove wire:target="submitQuiz">
                                            <i class="bi bi-check-circle me-1"></i> تسليم الاختبار
                                        </span>
                                        <span wire:loading wire:target="submitQuiz">
                                            <span class="spinner-border spinner-border-sm me-1"></span> جاري التسليم...
                                        </span>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary" wire:click="nextQuestion">
                                        <span wire:loading.remove wire:target="nextQuestion">
                                            التالي <i class="bi bi-arrow-left ms-1"></i>
                                        </span>
                                        <span wire:loading wire:target="nextQuestion">
                                            <span class="spinner-border spinner-border-sm"></span>
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Sidebar: Question Grid --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 140px;">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-grid-3x3 me-2"></i>قائمة الأسئلة
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach ($questions as $index => $q)
                                @php
                                    $isAnswered =
                                        $q['question_type'] === 'essay'
                                            ? !empty(trim($essayAnswers[$q['id']] ?? ''))
                                            : !empty($answers[$q['id']]);
                                    $isCurrent = $index === $currentQuestionIndex;
                                @endphp
                                <button type="button" wire:click="goToQuestion({{ $index }})"
                                    class="btn btn-sm {{ $isCurrent ? 'btn-primary' : ($isAnswered ? 'btn-success' : 'btn-outline-secondary') }}"
                                    style="width: 38px; height: 38px; padding: 0; font-size: 13px; font-weight: 600;">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span>
                                <span class="badge bg-success rounded-circle me-1"
                                    style="width:10px;height:10px;display:inline-block;"></span>
                                تم الإجابة
                            </span>
                            <span>
                                <span class="badge bg-secondary rounded-circle me-1"
                                    style="width:10px;height:10px;display:inline-block;"></span>
                                لم يتم
                            </span>
                        </div>

                        @if (!$quizSubmitted)
                            <button type="button" class="btn btn-outline-danger w-100 mt-3" wire:click="submitQuiz"
                                wire:confirm="هل أنت متأكد؟ سيتم تسليم الاختبار فوراً.">
                                <span wire:loading.remove wire:target="submitQuiz">
                                    <i class="bi bi-send me-1"></i> تسليم مبكر
                                </span>
                                <span wire:loading wire:target="submitQuiz">
                                    <span class="spinner-border spinner-border-sm me-1"></span> جاري...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Quiz Info --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">معلومات الاختبار</h6>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2">
                                <i class="bi bi-clock me-2 text-primary"></i>
                                الوقت:
                                {{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes . ' دقيقة' : 'غير محدود' }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-star me-2 text-warning"></i>
                                درجة النجاح: {{ $quiz->passing_score_percentage }}%
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-arrow-repeat me-2 text-info"></i>
                                المحاولات المتبقية:
                                @php $remaining = $quiz->remainingAttempts(Auth::user()); @endphp
                                {{ $remaining == -1 ? 'غير محدود' : $remaining }}
                            </li>
                            <li>
                                <i class="bi bi-shuffle me-2 text-secondary"></i>
                                {{ $quiz->shuffle_questions ? 'ترتيب عشوائي' : 'ترتيب ثابت' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
