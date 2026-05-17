@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>Courses Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">All Courses</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">All Courses</h5>
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add New Course
                            </a>
                        </div>

                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
                                    <th>Instructor</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img src="{{ $course->thumbnail_url }}" width="50" class="rounded"></td>
                                        <td>{{ $course->title }}</td>
                                        <td>{{ $course->instructor->name }}</td>
                                        <td>{{ $course->category->name }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $course->status == 'published' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if (Auth::user()->isAdmin() || (Auth::user()->isInstructor() && $course->instructor_id === Auth::id()))
                                                <a href="{{ route('admin.courses.edit', $course->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.courses.destroy', $course->id) }}"
                                                    method="POST" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('admin.courses.content', $course->id) }}"
                                                class="btn btn-info btn-sm text-white">
                                                <i class="fas fa-book-open"></i> Content
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No courses found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
