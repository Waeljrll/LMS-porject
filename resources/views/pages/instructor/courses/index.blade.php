@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>My Courses Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">My Courses</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">My Courses</h5>
                            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add New Course
                            </a>
                        </div>

                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
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
                                        <td>{{ $course->category->name }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $course->status == 'published' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('instructor.courses.edit', $course->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>


                                            <form action="{{ route('instructor.courses.destroy', $course->id) }}"
                                                method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا الكورس؟')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('instructor.courses.content', $course->id) }}"
                                                class="btn btn-info btn-sm text-white" title="إدارة المحتوى والدروس">
                                                <i class="fas fa-book-open"></i> محتوى الكورس
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No courses found. Create your first course
                                            now!</td>
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
