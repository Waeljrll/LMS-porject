@extends('layouts.app')

@section('front-content')
    <div class="container-fluid py-4">
        @livewire('instructor.question-builder', ['quiz' => $quiz])
    </div>
@endsection
