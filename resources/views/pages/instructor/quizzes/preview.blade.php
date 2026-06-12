@extends('layouts.app')

@section('front-content')
<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('instructor.courses.content', $quiz->section->course) }}">
                            {{ $quiz->section->course->title }}
                        </a>
                    </li>
                    <li class="breadcrumb-item text-muted">{{ $quiz->section->title }}</li>
                    <li class="breadcrumb-item active">معاينة الاختبار</li>
                </ol>
            </nav>
            <h4 class="mb-0 fw-bold">{{ $quiz->title }}</h4>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-info fs-6">
                <i class="bi bi-shuffle me-1"></i>
                {{ $quiz->shuffle_questions ? 'عشوائي' : 'مُرتب' }}
            </span>
            <a href="{{ route('instructor.quizzes.builder', $quiz->section) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> العودة للمُنشئ
            </a>
        </div>
    </div>

    {{-- Quiz Info Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <div>
                <h5 class="mb-1">وضع المعاينة</h5>
                <div class="text-muted small">معاينة تفاعلية. النتائج لا تُحفظ.</div>
            </div>
            <span class="badge bg-primary">{{ $questions->sum('points') }} نقطة</span>
        </div>
        <div class="card-body">
            @if ($quiz->description)
                <p class="mb-2">{{ $quiz->description }}</p>
            @endif
            @if ($quiz->instructions)
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>{{ $quiz->instructions }}
                </div>
            @endif
            @if ($quiz->time_limit_minutes)
                <div class="alert alert-warning mb-0 mt-2">
                    <i class="bi bi-clock me-2"></i>الوقت المحدد: {{ $quiz->time_limit_minutes }} دقيقة
                </div>
            @endif
        </div>
    </div>

    {{-- Questions List --}}
    @foreach ($questions as $index => $question)
        <div class="card shadow-sm border-0 mb-4" id="question-{{ $index }}">
            <div class="card-body p-4">
                {{-- Question Header --}}
                <div class="d-flex justify-content-between gap-3 mb-3">
                    <div class="d-flex gap-2 align-items-start">
                        <span class="badge bg-secondary align-self-start fs-6">س{{ $index + 1 }}</span>
                        <div class="trix-content">{!! $question->question_text !!}</div>
                    </div>
                    <span class="badge bg-warning text-dark align-self-start">{{ $question->points }} نقطة</span>
                </div>

                {{-- Question Image --}}
                @if ($question->image_path)
                    <div class="mb-3">
                        <img src="{{ $question->imageUrl() }}"
                             alt="صورة السؤال"
                             class="img-fluid rounded border"
                             style="max-height: 320px;">
                    </div>
                @endif

                {{-- MCQ Options --}}
                @if ($question->isMcq())
                    <div class="list-group mb-3">
                        @foreach ($question->options as $optionIndex => $option)
                            <div class="list-group-item d-flex align-items-center gap-3 p-3 mb-2 rounded border-2 border-light">
                                <div class="form-check m-0">
                                    <input class="form-check-input" type="radio" disabled>
                                </div>
                                <span class="flex-grow-1">{{ $option->option_text }}</span>
                                @if($option->is_correct)
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- True/False Options --}}
                @if ($question->isTrueFalse())
                    <div class="d-flex gap-3 mb-3">
                        @foreach ($question->options as $option)
                            <div class="card flex-fill text-center py-3 border-2
                                @if($option->is_correct) border-success bg-success bg-opacity-10
                                @else border-light
                                @endif">
                                @if($option->option_text == 'True' || $option->option_text == 'صح')
                                    <i class="bi bi-check-circle-fill text-success fs-3 mb-1"></i>
                                    <div class="fw-semibold text-success">صح (True)</div>
                                @else
                                    <i class="bi bi-x-circle-fill text-danger fs-3 mb-1"></i>
                                    <div class="fw-semibold text-danger">خطأ (False)</div>
                                @endif
                                @if($option->is_correct)
                                    <i class="bi bi-check-circle-fill text-success position-absolute top-0 end-0 m-2"></i>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Essay Answer --}}
                @if ($question->isEssay())
                    <div class="mb-3">
                        <textarea class="form-control" rows="5" disabled placeholder="سيتم كتابة الإجابة هنا..."></textarea>
                        <div class="form-text">سؤال مقالي - يتم تصحيحه يدوياً.</div>
                    </div>
                @endif

                {{-- Explanation --}}
                @if ($question->explanation)
                    <div class="alert alert-info border-start border-4 border-primary">
                        <div class="fw-bold mb-2 text-primary">
                            <i class="bi bi-lightbulb me-1"></i>الشرح
                        </div>
                        <div class="trix-content">{!! $question->explanation !!}</div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    {{-- Quiz Summary Footer --}}
    <div class="card shadow-sm border-0">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">ملخص الاختبار</h6>
                <div class="text-muted small">
                    {{ $questions->count() }} أسئلة | {{ $questions->sum('points') }} نقطة إجمالية
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('instructor.quizzes.builder', $quiz->section) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> تعديل الاختبار
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
