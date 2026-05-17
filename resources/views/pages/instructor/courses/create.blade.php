@extends('layouts.app')

@section('title', 'Create My Course')

@section('front-content')
    <div class="pagetitle">
        <h1>Create New Course</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">My Courses</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                @livewire('admin.course-wizard')
            </div>
        </div>
    </section>
@endsection
