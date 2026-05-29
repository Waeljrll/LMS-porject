@extends('layouts.app')

@section('front-content')

    <div class="pagetitle">
        <h1>Welcome back, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-muted">Here's what's happening with your courses today</p>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">

            <div class="col-lg-3 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">My Courses</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $totalCourses }}</h6>
                                <span class="text-muted small">{{ $publishedCourses }} published, {{ $draftCourses }}
                                    draft</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Students</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $totalStudents }}</h6>
                                <span class="text-muted small">Enrolled across all courses</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card customers-card">
                    <div class="card-body">
                        <h5 class="card-title">Avg. Rating</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($avgRating, 1) }} <small class="text-muted fs-6">/ 5.0</small></h6>
                                <span class="text-muted small">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card"
                    style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: white;">Revenue</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                                style="background: rgba(255,255,255,0.2); color: white;">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="ps-3">
                                <h6 style="color: white;">${{ number_format($totalRevenue, 2) }}</h6>
                                <span class="small" style="color: rgba(255,255,255,0.8);">Total course value</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
                            <h5 class="card-title mb-0 p-0">
                                <i class="bi bi-journal-bookmark text-primary me-2"></i>My Courses
                            </h5>
                            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Create New Course
                            </a>
                        </div>

                        @if ($totalCourses > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Course</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Students</th>
                                            <th>Rating</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($myCourses->take(5) as $course)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $course->thumbnail_url ?? asset('assets/img/default-course.jpg') }}"
                                                            width="50" class="rounded me-3" style="object-fit: cover;">
                                                        <div>
                                                            <strong
                                                                class="text-dark">{{ Str::limit($course->title, 30) }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span
                                                        class="badge bg-info text-dark">{{ $course->category->name }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $course->status == 'published' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($course->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="bi bi-people me-1 text-muted"></i>
                                                    {{ $course->enrollments_count }}
                                                </td>
                                                <td>
                                                    @if ($course->reviews_count > 0)
                                                        <span class="text-warning">
                                                            <i class="bi bi-star-fill"></i>
                                                            {{ number_format($course->reviews_avg_rating, 1) }}
                                                        </span>
                                                        <small class="text-muted">({{ $course->reviews_count }})</small>
                                                    @else
                                                        <span class="text-muted small">No reviews</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($course->price == 0)
                                                        <span class="badge bg-success">Free</span>
                                                    @else
                                                        <span
                                                            class="fw-bold text-success">${{ number_format($course->price, 2) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('instructor.courses.edit', $course->id) }}"
                                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a href="{{ route('instructor.courses.content', $course->id) }}"
                                                            class="btn btn-sm btn-outline-success" title="Content">
                                                            <i class="bi bi-folder"></i>
                                                        </a>
                                                        <a href="{{ route('instructor.courses.analytics', $course->id) }}"
                                                            class="btn btn-sm btn-info text-white">
                                                            <i class="fas fa-chart-bar"></i> analytics
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($totalCourses > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('instructor.courses.my') }}" class="btn btn-outline-primary btn-sm">
                                        View All {{ $totalCourses }} Courses <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-journal-plus display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">No courses yet</h5>
                                <p class="text-muted">Create your first course and start teaching!</p>
                                <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Create Course
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($totalStudents > 0)
                <div class="col-lg-6 mt-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title pt-3">
                                <i class="bi bi-people-fill text-success me-2"></i>Recent Enrollments
                            </h5>

                            <div class="activity mt-3">
                                @forelse($recentEnrollments as $enrollment)
                                    <div class="activity-item d-flex mb-3">
                                        <div class="activite-label text-muted small me-3" style="min-width: 80px;">
                                            {{ $enrollment->enrolled_at?->diffForHumans() ?? 'Recently' }}
                                        </div>
                                        <i class="bi bi-circle-fill activity-badge text-success align-self-start me-3"
                                            style="font-size: 10px; margin-top: 5px;"></i>
                                        <div class="activity-content">
                                            <strong>{{ $enrollment->student->name ?? 'Student' }}</strong> enrolled in
                                            <a href="#"
                                                class="fw-bold text-primary text-decoration-none">{{ Str::limit($enrollment->course->title, 25) }}</a>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center">No recent activity.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-lg-6 mt-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title pt-3">
                            <i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions
                        </h5>
                        <div class="row g-3 mt-1">
                            <div class="col-6">
                                <a href="{{ route('instructor.courses.create') }}"
                                    class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                                    New Course
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('instructor.courses.my') }}"
                                    class="btn btn-outline-success w-100 py-3">
                                    <i class="bi bi-journal-text fs-4 d-block mb-2"></i>
                                    My Courses
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('instructor.courses.index') }}"
                                    class="btn btn-outline-info w-100 py-3">
                                    <i class="bi bi-globe fs-4 d-block mb-2"></i>
                                    Browse All
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="bi bi-person-gear fs-4 d-block mb-2"></i>
                                    Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
