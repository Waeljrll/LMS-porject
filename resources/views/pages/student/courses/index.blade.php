@extends('layouts.app')

@section('front-content')
    {{-- Page Header --}}
    <div class="bg-light border-bottom py-4">
        <div class="container">
            <h4 class="mb-1 fw-bold">Browse Courses <i class="bi bi-globe ms-2 text-primary"></i></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Browse Courses</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-4">
        <livewire:student.browse-courses />
    </div>
@endsection
