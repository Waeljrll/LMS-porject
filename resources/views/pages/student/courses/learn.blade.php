@extends('layouts.app')

@section('front-content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">

    {{-- Header: Progress & Navigation --}}
    <div class="row mb-4 align-items-center bg-white p-3 mx-1 rounded shadow-sm">
        <div class="col-md-4">
            <a href="{{ route('student.enrollments.index') }}" class="btn btn-outline-secondary btn-sm fw-bold">
                <i class="fas fa-arrow-left me-1"></i> العودة للكورسات
            </a>
        </div>
        <div class="col-md-4 text-center">
            <h5 class="mb-0 fw-bold text-dark">{{ $course->title }}</h5>
        </div>

        {{-- Progress Bar (Alpine.js) --}}
        <div class="col-md-4" x-data="{ progress: {{ $enrollment->progress_percentage ?? 0 }} }" @progress-updated.window="progress = $event.detail.progress">
            <div class="d-flex align-items-center justify-content-end">
                <span class="me-3 small fw-bold text-muted" x-text="progress + '% Complete'">
                    {{ $enrollment->progress_percentage ?? 0 }}% Complete
                </span>
                <div class="progress" style="width: 150px; height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar" x-bind:style="'width: ' + progress + '%'"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mx-1">
        {{-- Main Content --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-img-top bg-dark text-white" style="height: 500px;">
                    @include('layouts.partials.lesson-player', ['lesson' => $lesson])
                </div>

                <div class="card-body p-4">
                    <h3 class="card-title fw-bold mb-4">{{ $lesson->title }}</h3>
                    <div class="lesson-content text-muted lh-lg">
                        {!! $lesson->text_content ?? 'لا يوجد محتوى نصي لهذا الدرس.' !!}
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0 p-4 d-flex justify-content-between align-items-center">
                    {{-- Previous Button --}}
                    <div>
                        @if (!$lesson->isFirstLesson())
                            <a href="{{ route('student.courses.learn', [$course, $lesson->previousLesson()]) }}" class="btn btn-light border fw-bold">
                                &laquo; السابق
                            </a>
                        @endif
                    </div>

                    <livewire:student.lesson-complete-button :lesson="$lesson" :course="$course" />

                    {{-- Next Button --}}
                    <div>
                        @if (!$lesson->isLastLesson())
                            <a href="{{ route('student.courses.learn', [$course, $lesson->nextLesson()]) }}" class="btn btn-primary fw-bold">
                                التالي &raquo;
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Curriculum Sidebar --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white p-3 border-bottom-0">
                    <h5 class="card-title mb-0 fw-bold">محتوى الكورس</h5>
                </div>
                <div class="card-body p-0">
                    <div class="accordion accordion-flush" id="curriculumAccordion">
                        @foreach ($sections as $section)
                            <div class="accordion-item border-bottom">
                                <h2 class="accordion-header" id="heading-{{ $section->id }}">
                                    <button class="accordion-button fw-bold {{ $section->id == $lesson->section_id ? '' : 'collapsed' }} bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $section->id }}">
                                        {{ $section->title }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $section->id }}" class="accordion-collapse collapse {{ $section->id == $lesson->section_id ? 'show' : '' }}" data-bs-parent="#curriculumAccordion">
                                    <div class="list-group list-group-flush">
                                        @foreach ($section->lessons as $l)
                                            <a href="{{ route('student.courses.learn', [$course, $l]) }}" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center {{ $l->id == $lesson->id ? 'bg-primary bg-opacity-10 border-start border-primary border-4' : '' }}">
                                                <div class="d-flex align-items-center">
                                                    {{-- Completion Icon --}}
                                                    <i class="bi {{ auth()->user()->completedLessons->contains($l->id) ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }} me-2"></i>

                                                    @if ($l->type === 'video')
                                                        <i class="fas fa-play-circle text-muted me-2"></i>
                                                    @else
                                                        <i class="fas fa-file-alt text-muted me-2"></i>
                                                    @endif
                                                    <span class="{{ $l->id == $lesson->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                                        {{ $l->title }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">{{ $l->duration_minutes ?? '0' }}m</small>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
