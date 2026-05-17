@extends('layouts.app')

@section('front-content')

<div class="pagetitle">
    <h1>Welcome back, {{ auth()->user()->name }}! 👋</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

@php
    $user = auth()->user();
    $enrolledCourses = $user->enrollments()->with('course')->get();
    $totalEnrolled = $enrolledCourses->count();
    $completedCourses = $enrolledCourses->where('status', 'completed')->count();
    $inProgressCourses = $enrolledCourses->where('status', 'active')->count();
    $certificatesEarned = $enrolledCourses->where('status', 'completed')->count(); // Until certificate system is built
@endphp

<section class="section dashboard">
    <div class="row">

        <!-- Stats Cards -->
        <div class="col-lg-3 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">My Courses</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $totalEnrolled }}</h6>
                            <span class="text-muted small">Enrolled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $completedCourses }}</h6>
                            <span class="text-muted small">Finished</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">In Progress</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $inProgressCourses }}</h6>
                            <span class="text-muted small">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card customers-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <h5 class="card-title" style="color: white;">Certificates</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.2); color: white;">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="color: white;">{{ $certificatesEarned }}</h6>
                            <span class="small" style="color: rgba(255,255,255,0.8);">Earned</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Continue Learning Section -->
        @if($inProgressCourses > 0)
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-play-circle text-primary me-2"></i>Continue Learning
                        </h5>
                        <a href="{{ route('student.courses.my') }}" class="btn btn-outline-primary btn-sm">
                            View All <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="row">
                        @foreach($enrolledCourses->where('status', 'active')->take(3) as $enrollment)
                            @php
                                $course = $enrollment->course;
                                $progress = $enrollment->progress_percentage ?? 0;
                                $totalLessons = $course->lessons()->count();
                                $completedLessons = \App\Models\Lesson_Progress::where('student_id', $user->id)
                                    ->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))
                                    ->where('is_completed', true)
                                    ->count();
                                $actualProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                            @endphp
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <img src="{{ $course->thumbnail_url }}" class="card-img-top"
                                         alt="{{ $course->title }}" style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($course->title, 40) }}</h6>
                                        <p class="text-muted small mb-2">{{ $course->instructor->name }}</p>

                                        <div class="progress mb-2" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $actualProgress }}%"
                                                 aria-valuenow="{{ $actualProgress }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">{{ $actualProgress }}% complete</small>
                                            <small class="text-muted">{{ $completedLessons }}/{{ $totalLessons }} lessons</small>
                                        </div>

                                        <a href="#" class="btn btn-primary btn-sm w-100 mt-3">
                                            <i class="bi bi-play-fill"></i> Continue
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Browse Courses CTA -->
        <div class="col-12 mt-4">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title text-white mb-1">Discover New Courses</h5>
                        <p class="mb-0 text-white-50">Expand your knowledge with our latest offerings</p>
                    </div>
                    <a href="{{ route('student.courses.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-globe me-2"></i>Browse Courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Enrollments -->
        @if($totalEnrolled > 0)
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recently Enrolled</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Instructor</th>
                                    <th>Progress</th>
                                    <th>Enrolled At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrolledCourses->take(5) as $enrollment)
                                    @php
                                        $course = $enrollment->course;
                                        $progress = $enrollment->progress_percentage ?? 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ $course->thumbnail_url }}" width="40" class="rounded me-2" style="object-fit: cover;">
                                            {{ Str::limit($course->title, 30) }}
                                        </td>
                                        <td>{{ $course->instructor->name }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px; width: 100px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $progress }}%</small>
                                        </td>
                                        <td>{{ $enrollment->enrolled_at?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-play-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
