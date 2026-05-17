@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>User Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>

    @php
        $isInstructor = $user->isInstructor();
        $isStudent = $user->isStudent();

        // Instructor stats
        $instructorCourses = $isInstructor ? $user->courses()->withCount('enrollments')->get() : collect();
        $instructorTotalCourses = $instructorCourses->count();
        $instructorTotalStudents = $isInstructor ? \App\Models\Enrollment::whereIn('course_id', $instructorCourses->pluck('id'))->count() : 0;
        $instructorAvgRating = $isInstructor ? \App\Models\Review::whereIn('course_id', $instructorCourses->pluck('id'))->avg('rating') ?? 0 : 0;

        // Student stats
        $studentEnrollments = $isStudent ? $user->enrollments()->with('course')->latest()->get() : collect();
        $studentTotalEnrolled = $studentEnrollments->count();
        $studentCompleted = $studentEnrollments->where('status', 'completed')->count();
        $studentInProgress = $studentEnrollments->where('status', 'active')->count();

        // Last login
        $lastLogin = $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never';
    @endphp

    <section class="section profile">
        <div class="row">
            <!-- Left Column - Profile Card -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ $user->imageUrl() }}" alt="Profile" class="rounded-circle"
                            style="width: 120px; height: 120px; object-fit: cover;">
                        <h2 class="mt-3">{{ $user->name }}</h2>
                        <h3 class="text-muted small">{{ ucfirst($user->role) }}</h3>

                        <div class="mt-3 w-100">
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Status</span>
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Joined</span>
                                <span>{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Last Login</span>
                                <span>{{ $lastLogin }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Member Since</span>
                                <span>{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role-specific Stats Card -->
                @if($isInstructor)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Instructor Stats</h5>
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="text-primary">{{ $instructorTotalCourses }}</h4>
                                <small class="text-muted">Courses</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-success">{{ $instructorTotalStudents }}</h4>
                                <small class="text-muted">Students</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-warning">{{ number_format($instructorAvgRating, 1) }}</h4>
                                <small class="text-muted">Avg Rating</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($isStudent)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Student Stats</h5>
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="text-primary">{{ $studentTotalEnrolled }}</h4>
                                <small class="text-muted">Enrolled</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-success">{{ $studentCompleted }}</h4>
                                <small class="text-muted">Completed</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-info">{{ $studentInProgress }}</h4>
                                <small class="text-muted">In Progress</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Details -->
            <div class="col-xl-8">
                <!-- Profile Information -->
                <div class="card">
                    <div class="card-body pt-3">
                        <h5 class="card-title">Profile Information</h5>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Full Name</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Email</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Phone</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">{{ $user->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Bio</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">{{ $user->bio ?? 'No bio available' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Role</div>
                            <div class="col-lg-9 col-md-8 border-bottom pb-1">
                                <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label text-primary fw-bold">Account Status</div>
                            <div class="col-lg-9 col-md-8">
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructor Courses -->
                @if($isInstructor && $instructorTotalCourses > 0)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-journal-text text-primary me-2"></i>Courses Created
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Status</th>
                                        <th>Students</th>
                                        <th>Rating</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($instructorCourses as $course)
                                        @php
                                            $courseRating = $course->reviews->avg('rating') ?? 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $course->thumbnail_url }}" width="40" class="rounded me-2" style="object-fit: cover;">
                                                    <span>{{ Str::limit($course->title, 30) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $course->status == 'published' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($course->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $course->enrollments_count }}</td>
                                            <td>
                                                <span class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= round($courseRating) ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </span>
                                                <small>({{ number_format($courseRating, 1) }})</small>
                                            </td>
                                            <td>
                                                @if($course->price == 0)
                                                    <span class="badge bg-success">Free</span>
                                                @else
                                                    ${{ number_format($course->price, 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Student Enrollments -->
                @if($isStudent && $studentTotalEnrolled > 0)
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-book text-success me-2"></i>Enrolled Courses
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Instructor</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Enrolled</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentEnrollments as $enrollment)
                                        @php
                                            $course = $enrollment->course;
                                            $progress = $enrollment->progress_percentage ?? 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $course->thumbnail_url }}" width="40" class="rounded me-2" style="object-fit: cover;">
                                                    <span>{{ Str::limit($course->title, 30) }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $course->instructor->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="progress" style="height: 6px; width: 100px;">
                                                    <div class="progress-bar bg-{{ $progress == 100 ? 'success' : 'primary' }}"
                                                         role="progressbar" style="width: {{ $progress }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $progress }}%</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $enrollment->status == 'completed' ? 'success' : ($enrollment->status == 'active' ? 'primary' : 'secondary') }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $enrollment->enrolled_at?->format('M d, Y') ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <div class="text-end mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to Users
                    </a>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit User
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
