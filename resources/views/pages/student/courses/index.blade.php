@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>Browse Courses</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">All Courses</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            @forelse($courses as $course)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $course->thumbnail_url }}" class="card-img-top" alt="{{ $course->title }}"
                            style="height: 200px; object-fit: cover;">

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary">{{ $course->category->name }}</span>
                                <span
                                    class="badge bg-{{ $course->difficulty_level == 'beginner' ? 'success' : ($course->difficulty_level == 'intermediate' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($course->difficulty_level) }}
                                </span>
                            </div>

                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($course->short_description, 100) }}</p>

                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $course->instructor->imageUrl() }}" class="rounded-circle me-2" width="30"
                                    height="30" style="object-fit: cover;">
                                <small class="text-muted">{{ $course->instructor->name }}</small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">
                                    @if ($course->price == 0)
                                        Free
                                    @else
                                        ${{ number_format($course->price, 2) }}
                                    @endif
                                </span>

                                @php
                                    $showRoute = auth()->user()->isStudent()
                                        ? route('student.courses.show', $course->id)
                                        : route('courses.show', $course->id);
                                @endphp

                                <a href="{{ $showRoute }}" class="btn btn-outline-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>

                        <div class="card-footer bg-white">
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> {{ $course->duration_hours }}h
                                {{ $course->duration_minutes }}m
                                <span class="ms-2"><i class="bi bi-people"></i> {{ $course->enrollments_count ?? 0 }}
                                    students</span>
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">No courses available yet</h4>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $courses->links() }}
        </div>
    </section>
@endsection
