@extends('layouts.app')

@section('front-content')

    <div class="pagetitle mb-4">
        <h1>My Courses 📚</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">My Courses</li>
            </ol>
        </nav>
    </div>

    <section class="section my-courses">
        <div class="card border-0 shadow-sm">
            <div class="card-body pt-3">

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs nav-fill mb-3" id="courseTabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="active-tab" data-bs-toggle="tab"
                            data-bs-target="#active-courses" type="button" role="tab">
                            Active <span class="badge bg-primary ms-1">{{ $activeEnrollments->count() }}</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center justify-content-center" id="completed-tab"
                            data-bs-toggle="tab" data-bs-target="#completed-courses" type="button" role="tab">
                            Completed <span class="badge bg-success ms-1">{{ $completedEnrollments->count() }}</span>
                        </button>
                    </li>

                </ul>

                <!-- Tabs Content -->
                <div class="tab-content pt-2" id="courseTabsContent">

                    <!-- Active Courses Tab -->
                    <div class="tab-pane fade show active" id="active-courses" role="tabpanel" aria-labelledby="active-tab">
                        @if ($activeEnrollments->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-3 text-muted">You are not enrolled in any active courses right now.</p>
                                <a href="{{ route('student.courses.index') }}" class="btn btn-primary mt-2">Browse
                                    Courses</a>
                            </div>
                        @else
                            <div class="tab-pane fade show active" id="active-courses" role="tabpanel">
                                <div class="row">
                                    @forelse ($activeEnrollments as $enrollment)
                                        @include('layouts.partials._course-card', [
                                            'enrollment' => $enrollment,
                                        ])
                                    @empty
                                        <p>No active courses.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Completed Courses Tab -->
                    <div class="tab-pane fade" id="completed-courses" role="tabpanel" aria-labelledby="completed-tab">
                        @if ($completedEnrollments->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-award text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-3 text-muted">You haven't completed any courses yet. Keep learning!</p>
                            </div>
                        @else
                            <div class="row">
                                @foreach ($completedEnrollments as $enrollment)
                                    <div class="col-xl-4 col-md-6 mb-4">
                                        <div class="card h-100 border-0 shadow-sm custom-course-card">
                                            <img src="{{ $enrollment->course->thumbnail_url }}" class="card-img-top"
                                                alt="{{ $enrollment->course->title }}"
                                                style="height: 180px; object-fit: cover;">
                                            <div class="card-body d-flex flex-column pt-3">
                                                <h5 class="card-title p-0 mb-2"
                                                    style="font-size: 1.1rem; line-height: 1.4;">
                                                    {{ Str::limit($enrollment->course->title, 50) }}
                                                </h5>
                                                <p class="text-muted small mb-3">By
                                                    {{ $enrollment->course->instructor->name }}</p>

                                                <div class="mt-auto">
                                                    <div
                                                        class="alert alert-success py-2 px-3 mb-3 d-flex align-items-center border-0 small">
                                                        <i class="bi bi-check-circle-fill me-2"></i> Completed on
                                                        {{ $enrollment->updated_at?->format('M d, Y') ?? 'N/A' }}
                                                    </div>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <a href="#"
                                                                class="btn btn-outline-secondary btn-sm w-100">
                                                                <i class="bi bi-arrow-counterclockwise"></i> Review
                                                            </a>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="#" class="btn btn-success btn-sm w-100">
                                                                <i class="bi bi-award"></i> Certificate
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div><!-- End Tabs Content -->

            </div>
        </div>
    </section>

@endsection
