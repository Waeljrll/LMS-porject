@extends('layouts.app')

@section('front-content')

@php
    $totalUsers = \App\Models\User::count();
    $totalStudents = \App\Models\User::where('role', 'student')->count();
    $totalInstructors = \App\Models\User::where('role', 'instructor')->count();
    $totalAdmins = \App\Models\User::where('role', 'admin')->count();
    $totalCourses = \App\Models\Course::count();
    $publishedCourses = \App\Models\Course::where('status', 'published')->count();
    $draftCourses = \App\Models\Course::where('status', 'draft')->count();
    $totalEnrollments = \App\Models\Enrollment::count();
    $totalRevenue = \App\Models\Course::where('status', 'published')->sum('price');
    $recentUsers = \App\Models\User::latest()->take(10)->get();
    $recentEnrollments = \App\Models\Enrollment::with(['student', 'course'])->latest()->take(10)->get();
@endphp

<div class="pagetitle">
    <h1>Welcome back, {{ auth()->user()->name }}! 👋</h1>
    <p class="text-muted">Platform overview and management</p>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">

        <!-- Stats Cards Row 1 -->
        <div class="col-lg-3 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $totalUsers }}</h6>
                            <span class="text-muted small">
                                {{ $totalStudents }} Students | {{ $totalInstructors }} Instructors | {{ $totalAdmins }} Admins
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Total Courses</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $totalCourses }}</h6>
                            <span class="text-muted small">
                                {{ $publishedCourses }} Published | {{ $draftCourses }} Draft
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Total Enrollments</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $totalEnrollments }}</h6>
                            <span class="text-muted small">Across all courses</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <h5 class="card-title" style="color: white;">Total Revenue</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.2); color: white;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="color: white;">${{ number_format($totalRevenue, 2) }}</h6>
                            <span class="small" style="color: rgba(255,255,255,0.8);">From published courses</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-12 mt-2">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <i class="bi bi-people me-1"></i> Manage Users
                </a>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-success">
                    <i class="bi bi-journal-bookmark me-1"></i> Manage Courses
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-info">
                    <i class="bi bi-tags me-1"></i> Categories
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-warning">
                    <i class="bi bi-person-plus me-1"></i> Add User
                </a>
            </div>
        </div>

        <!-- Recent Registrations -->
        <div class="col-lg-6 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-plus text-primary me-2"></i>Recent Registrations
                        </h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->imageUrl() }}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    <br><small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-info">{{ ucfirst($user->role) }}</span></td>
                                        <td>
                                            <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Enrollments -->
        <div class="col-lg-6 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-check text-success me-2"></i>Recent Enrollments
                        </h5>
                        <span class="badge bg-primary">{{ $totalEnrollments }} Total</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Progress</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentEnrollments as $enrollment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $enrollment->student->imageUrl() }}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                                <span>{{ Str::limit($enrollment->student->name, 15) }}</span>
                                            </div>
                                        </td>
                                        <td>{{ Str::limit($enrollment->course->title, 25) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px; width: 60px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $enrollment->progress_percentage ?? 0 }}%</small>
                                        </td>
                                        <td>{{ $enrollment->enrolled_at?->diffForHumans() ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Courses -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-trophy text-warning me-2"></i>Top Performing Courses
                    </h5>
                    @php
                        $topCourses = \App\Models\Course::withCount('enrollments')
                            ->with('instructor')
                            ->orderBy('enrollments_count', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Instructor</th>
                                    <th>Enrollments</th>
                                    <th>Rating</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCourses as $index => $course)
                                    <tr>
                                        <td>
                                            @if($index < 3)
                                                <i class="bi bi-trophy-fill text-{{ ['warning', 'secondary', 'danger'][$index] }}"></i>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $course->thumbnail_url }}" width="40" class="rounded me-2" style="object-fit: cover;">
                                                <strong>{{ Str::limit($course->title, 35) }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $course->instructor->name }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $course->enrollments_count }}</span>
                                        </td>
                                        <td>
                                            @php $rating = $course->reviews->avg('rating') ?? 0; @endphp
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}"></i>
                                                @endfor
                                            </span>
                                            <small class="text-muted">({{ number_format($rating, 1) }})</small>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($course->price * $course->enrollments_count, 2) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
