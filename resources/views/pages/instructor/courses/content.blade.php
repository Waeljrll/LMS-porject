@extends('layouts.app')

@section('front-content')
    <div class="container-fluid px-4 py-4">
        @php $role = auth()->user()->isAdmin() ? 'admin' : 'instructor'; @endphp

        @include('partials.course-curriculum', ['role' => $role, 'course' => $course])
    </div>
@endsection
