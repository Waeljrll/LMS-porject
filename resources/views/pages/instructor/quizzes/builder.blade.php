@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.15/dist/trix.css">
    <style>
        [x-cloak] { display: none !important; }
        trix-editor.trix-content { min-height: 120px; border: none !important; box-shadow: none !important; padding: 0.5rem 0.75rem; }
        trix-toolbar .trix-button-group { border-radius: 6px; }
        .trix-wrapper { border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden; }
        .trix-wrapper:focus-within { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); }
    </style>
@endpush

@push('head-scripts')
    <script src="https://unpkg.com/trix@2.1.15/dist/trix.umd.min.js"></script>
@endpush

@section('front-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('instructor.courses.content', $section->course) }}">
                                {{ $section->course->title }}
                            </a>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ $section->title }}</li>
                        <li class="breadcrumb-item active">بناء الاختبار</li>
                    </ol>
                </nav>
                <h4 class="mb-0 fw-bold">{{ $quiz->title }}</h4>
            </div>
            <a href="{{ route('instructor.quizzes.preview', $quiz) }}"
               class="btn btn-outline-primary d-flex align-items-center gap-2">
                <i class="bi bi-eye"></i> معاينة الاختبار
            </a>
        </div>

        {{-- Livewire Component (Form + Questions List) --}}
        <livewire:instructor.question-builder
            :quiz="$quiz"
            :key="'qb-' . $quiz->id" />

        {{-- Quiz Settings --}}
        <div class="mt-4">
            <livewire:instructor.quiz-settings
                :quiz="$quiz"
                :key="'qs-' . $quiz->id" />
        </div>
    </div>
@endsection
