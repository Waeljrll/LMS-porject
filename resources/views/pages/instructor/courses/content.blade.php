@extends('layouts.app')

@section('front-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">إدارة محتوى الكورس: {{ $course->title }}</h1>
                <p class="text-muted mb-0">قم بتنظيم الكورس إلى فصول (Sections) ودروس (Lessons)</p>
            </div>
            <div class="d-flex gap-2">
                @php $role = auth()->user()->isAdmin() ? 'admin' : 'instructor'; @endphp

                <a href="{{ route($role . '.courses.show', $course->id) }}?preview=true" target="_blank"
                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-eye"></i> Preview Course
                </a>

                <a href="{{ route($role . '.courses.index') }}"
                    class="btn btn-secondary btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Back to Courses
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="m-0 font-weight-bold">إضافة فصل جديد (Add Section)</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route($role . '.sections.store', $course->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">عنوان الفصل *</label>
                                <input type="text" name="title" class="form-control" required
                                    placeholder="مثال: أساسيات الـ OOP">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">الوصف (اختياري)</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="نبذة مختصرة عن الفصل..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-sm">حفظ الفصل</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-dark font-weight-bold">مخطط الكورس الحالي (Curriculum)</h5>
                    </div>
                    <div class="card-body">
                        @if ($course->sections->count() == 0)
                            <div class="text-center py-5">
                                <i class="bi bi-folder-plus text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">لا يوجد أي فصول في هذا الكورس حتى الآن</h5>
                                <p class="text-muted small">استخدم الفورم الجانبية لإضافة الفصل الأول لتتمكن من رفع الدروس
                                    بداخله.</p>
                            </div>
                        @else
                            @foreach ($course->sections as $section)
                                <div class="card mb-3 border-left-primary shadow-none bg-light">
                                    <div
                                        class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                <i class="bi bi-grid-3x3-gap text-muted me-2"></i> {{ $section->title }}
                                            </h6>
                                            @if ($section->description)
                                                <small class="text-muted d-block mt-1">{{ $section->description }}</small>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-secondary text-white me-3">
                                                {{ $section->lessons->count() }} Lessons
                                            </span>

                                            <div class="btn-group">
                                                <form
                                                    action="{{ route($role . '.sections.reorder', [$section->id, 'up']) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-outline-secondary btn-sm" title="Move Up"><i
                                                            class="bi bi-arrow-up"></i></button>
                                                </form>
                                                <form
                                                    action="{{ route($role . '.sections.reorder', [$section->id, 'down']) }}"
                                                    method="POST" class="d-inline me-2">
                                                    @csrf
                                                    <button class="btn btn-outline-secondary btn-sm" title="Move Down"><i
                                                            class="bi bi-arrow-down"></i></button>
                                                </form>

                                                <a href="{{ route($role . '.lessons.create', $section->id) }}"
                                                    class="btn btn-outline-success btn-sm me-1">
                                                    <i class="bi bi-plus-circle"></i> Add Lesson
                                                </a>

                                                <button class="btn btn-outline-secondary btn-sm me-1" title="تعديل الفصل"><i
                                                        class="bi bi-pencil"></i></button>

                                                @if ($section->lessons->count() > 0)
                                                    <button class="btn btn-outline-danger btn-sm" disabled
                                                        title="لا يمكنك حذف فصل يحتوي على دروس!">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @else
                                                    <form action="{{ route($role . '.sections.destroy', $section->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا الفصل؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"><i
                                                                class="bi bi-trash"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body p-0 bg-white">
                                        <ul class="list-group list-group-flush">
                                            @if ($section->lessons->count() == 0)
                                                <li class="list-group-item text-muted text-center py-2 bg-light-soft">
                                                    <small><i class="bi bi-info-circle me-1"></i> لا توجد دروس في هذا الفصل
                                                        بعد.</small>
                                                </li>
                                            @else
                                                @foreach ($section->lessons as $lesson)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center px-4 py-2">
                                                        <div class="d-flex align-items-center">
                                                            @if ($lesson->lesson_type === 'video')
                                                                <i class="bi bi-play-btn-fill text-danger me-2"></i>
                                                            @else
                                                                <i class="bi bi-file-text-fill text-info me-2"></i>
                                                            @endif
                                                            <div>
                                                                <span class="text-dark">{{ $lesson->title }}</span>
                                                                <span
                                                                    class="text-muted small ms-2">({{ $lesson->duration_minutes }}
                                                                    mins)</span>
                                                                @if ($lesson->is_preview)
                                                                    <span class="badge bg-success ms-1">Free Preview</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-link text-secondary p-0 me-2"><i
                                                                    class="bi bi-pencil"></i></button>
                                                            <button class="btn btn-sm btn-link text-danger p-0"><i
                                                                    class="bi bi-trash"></i></button>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
