<div class="col-xl-4 col-md-6 mb-4">
    <div class="card h-100 border-0 shadow-sm">
        <img src="{{ $enrollment->course->thumbnail_url }}" class="card-img-top" style="height: 180px; object-fit: cover;">

        <div class="card-body d-flex flex-column">

            <h5 class="mb-3">{{ Str::limit($enrollment->course->title, 50) }}</h5>

            <div class="progress mb-2" style="height: 8px;">
                <div class="progress-bar bg-primary" style="width: {{ $enrollment->actual_progress }}%"></div>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <small class="fw-bold text-primary">{{ $enrollment->actual_progress }}%</small>
                <small class="text-muted">
                    {{ $enrollment->completed_lessons_count }} / {{ $enrollment->total_lessons_count }} Lessons
                </small>
            </div>

            <div class="mt-auto"></div>

            <a href="{{ route('student.courses.learn', $enrollment->course_id) }}" class="btn btn-success btn-lg w-100">
                <i class="bi bi-play-circle me-2"></i>Continue Learning
            </a>
        </div>
    </div>
</div>
