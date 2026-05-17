@extends('layouts.app')

@section('title', 'Create New Course')

@section('front-content')
<div class="pagetitle">
    <h1>Create New Course</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            @livewire('admin.course-wizard')
        </div>
    </div>
</section>
@endsection
