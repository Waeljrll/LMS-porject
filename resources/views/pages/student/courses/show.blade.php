@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>{{ $course->title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('student.courses.index') }}">Courses</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($course->title, 30) }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Course Header -->
                <div class="card mb-4">
                    <img src="{{ $course->thumbnail_url }}" class="card-img-top" alt="{{ $course->title }}"
                        style="height: 400px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-primary me-2">{{ $course->category->name }}</span>
                                <span
                                    class="badge bg-{{ $course->difficulty_level == 'beginner' ? 'success' : ($course->difficulty_level == 'intermediate' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($course->difficulty_level) }}
                                </span>
                            </div>
                            <div class="text-end">
                                @if ($course->price == 0)
                                    <span class="badge bg-success fs-6">Free</span>
                                @else
                                    <span class="badge bg-primary fs-6">${{ number_format($course->price, 2) }}</span>
                                @endif
                            </div>
                        </div>

                        <h2 class="card-title">{{ $course->title }}</h2>
                        <p class="text-muted">{{ $course->short_description }}</p>

                        <!-- Instructor Info -->
                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                            <img src="{{ $course->instructor->imageUrl() }}" class="rounded-circle me-3" width="60"
                                height="60" style="object-fit: cover;">
                            <div>
                                <h6 class="mb-1">{{ $course->instructor->name }}</h6>
                                <small class="text-muted">{{ $course->instructor->bio ?? 'Instructor' }}</small>
                            </div>
                        </div>

                        <!-- Enroll Button -->
                        @if (auth()->user()->isStudent())
                            @if (auth()->user()->enrollments()->where('course_id', $course->id)->exists())
                                <a href="#" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-play-circle"></i> Continue Learning
                                </a>
                            @else
                                <form action="{{ route('student.courses.enroll', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-cart-plus"></i>
                                        @if ($course->price == 0)
                                            Enroll Now - Free
                                        @else
                                            Enroll Now - ${{ number_format($course->price, 2) }}
                                        @endif
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- What You'll Learn -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-bullseye text-primary me-2"></i>What You'll Learn</h5>
                        <div class="row">
                            @foreach ($course->objectives as $objective)
                                <div class="col-md-6 mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    {{ $objective->objective }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Course Content Preview -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-list-ul text-primary me-2"></i>Course Content</h5>
                        @forelse($course->sections as $section)
                            <div class="mb-3">
                                <h6 class="fw-bold bg-light p-2 rounded">{{ $section->title }}</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach ($section->lessons as $lesson)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                <i
                                                    class="bi bi-{{ $lesson->type == 'video' ? 'play-circle' : 'file-text' }} me-2"></i>
                                                {{ $lesson->title }}
                                            </span>
                                            <span class="text-muted small">
                                                @if ($lesson->is_free)
                                                    <span class="badge bg-success">Free Preview</span>
                                                @else
                                                    <i class="bi bi-lock"></i>
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-muted">Course content will be available soon.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Requirements -->
                @if ($course->requirements)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-exclamation-circle text-primary me-2"></i>Requirements
                            </h5>
                            <p>{{ $course->requirements }}</p>
                        </div>
                    </div>
                @endif

                <!-- Who is this for -->
                @if ($course->who_is_it_for)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-people text-primary me-2"></i>Who This Course is For</h5>
                            <p>{{ $course->who_is_it_for }}</p>
                        </div>
                    </div>
                @endif

                <!-- Description -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-info-circle text-primary me-2"></i>Description</h5>
                        <p>{!! nl2br(e($course->description)) !!}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Info Card -->
                <div class="card mb-4 sticky-top" style="top: 80px; z-index: 100;">
                    <div class="card-body">
                        <h5 class="card-title">Course Details</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-clock me-2"></i>Duration</span>
                                <span class="fw-bold">{{ $course->duration_hours }}h
                                    {{ $course->duration_minutes }}m</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-globe me-2"></i>Language</span>
                                <span class="fw-bold">{{ $course->language }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-bar-chart me-2"></i>Level</span>
                                <span class="fw-bold">{{ ucfirst($course->difficulty_level) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-calendar me-2"></i>Last Updated</span>
                                <span class="fw-bold">{{ $course->updated_at->format('M Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="bi bi-people me-2"></i>Students</span>
                                <span class="fw-bold">{{ $course->enrollments_count ?? 0 }}</span>
                            </li>
                        </ul>

                        <!-- Enroll Button (Mobile/Sticky) -->
                        @if (auth()->user()->isStudent())
                            <div class="mt-3 d-lg-none">
                                @if (auth()->user()->enrollments()->where('course_id', $course->id)->exists())
                                    <a href="#" class="btn btn-success w-100">Continue Learning</a>
                                @else
                                    <form action="{{ route('student.courses.enroll', $course->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">
                                            @if ($course->price == 0)
                                                Enroll Free
                                            @else
                                                Enroll - ${{ number_format($course->price, 2) }}
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reviews Preview -->
                @if ($course->reviews->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reviews</h5>
                            <div class="d-flex align-items-center mb-3">
                                <h2 class="mb-0 me-2">{{ number_format($course->reviews->avg('rating'), 1) }}</h2>
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="bi bi-star{{ $i <= round($course->reviews->avg('rating')) ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                    <br>
                                    <small class="text-muted">{{ $course->reviews->count() }} reviews</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
