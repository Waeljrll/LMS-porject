@extends('layouts.app')

@section('front-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i> تعديل كورس خاص بي: {{ $course->title }}
                    </h5>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> رجوع لقائمتي
                    </a>
                </div>
                <div class="card-body">
                    @livewire('admin.course-wizard', ['id' => $course->id])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
