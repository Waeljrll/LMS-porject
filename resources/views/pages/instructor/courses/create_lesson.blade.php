@extends('layouts.app')

@section('front-content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 font-weight-bold">إضافة درس جديد إلى فصل: {{ $section->title }}</h5>
                        <small>تابع لكورس: {{ $course->title }}</small>
                    </div>
                    <div class="card-body p-4">
                        @php $role = auth()->user()->isAdmin() ? 'admin' : 'instructor'; @endphp
                        <form action="{{ route($role . '.lessons.store', ['section' => $section->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="section_id" value="{{ $section->id }}">

                            {{-- عنوان الدرس --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">عنوان الدرس *</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $lesson->title ?? '') }}" required
                                    placeholder="مثال: مقدمة إلى الكورس">
                            </div>

                            <div class="row mb-4">
                                {{-- المدة --}}
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">المدة بالدقائق *</label>
                                    <input type="number" name="duration_minutes" class="form-control"
                                        value="{{ old('duration_minutes', $lesson->duration_minutes ?? 1) }}" required
                                        min="1">
                                </div>
                            </div>

                            <hr class="my-4 text-muted">

                            <label class="form-label fw-bold mb-2">نوع محتوى الدرس *</label>
                            <div class="nav nav-pills nav-justified mb-3 card bg-light p-2 flex-row border-0"
                                id="lessonTypeTabs" role="tablist">
                                <button
                                    class="nav-link btn-sm py-2 {{ old('lesson_type', $lesson->lesson_type ?? 'video') === 'video' ? 'active bg-primary text-white' : 'text-dark' }}"
                                    id="tab-video" data-bs-toggle="pill" data-bs-target="#panel-video" type="button"
                                    role="tab" onclick="document.getElementById('lesson_type_input').value='video'">
                                    <i class="bi bi-play-btn-fill me-1"></i> درس فيديو (Video)
                                </button>
                                <button
                                    class="nav-link btn-sm py-2 {{ old('lesson_type', $lesson->lesson_type ?? 'video') === 'document' ? 'active bg-primary text-white' : 'text-dark' }}"
                                    id="tab-document" data-bs-toggle="pill" data-bs-target="#panel-document" type="button"
                                    role="tab" onclick="document.getElementById('lesson_type_input').value='document'">
                                    <i class="bi bi-file-text-fill me-1"></i> ملف نصي / مقال (Document)
                                </button>
                            </div>

                            <input type="hidden" name="lesson_type" id="lesson_type_input"
                                value="{{ old('lesson_type', $lesson->lesson_type ?? 'video') }}">

                            <div class="tab-content" id="lessonTypeContent">

                                {{-- لوحة الفيديو المعدلة لدعم الرفع الفعلي والرابط --}}
                                <div class="tab-pane fade {{ old('lesson_type', $lesson->lesson_type ?? 'video') === 'video' ? 'show active' : '' }}"
                                    id="panel-video" role="tabpanel">
                                    <div class="mb-3 card p-3 border-0 bg-light">
                                        <label class="form-label fw-bold text-danger">
                                            <i class="bi bi-cloud-arrow-up-fill"></i> خيار 1: رفع ملف فيديو مباشرة من جهازك
                                        </label>
                                        <input type="file" name="video_file" class="form-control" accept="video/*">
                                        <small class="text-muted d-block mt-1">امتدادات مدعومة: mp4, mov, ogg (الحد الأقصى:
                                            50 ميجا).</small>

                                        {{-- إشعار للمدرس لو الدرس جواه فيديو مرفوع حالياً --}}
                                        @if (isset($lesson) && $lesson->video_url && !filter_var($lesson->video_url, FILTER_VALIDATE_URL))
                                            <div class="alert alert-info mt-2 py-1 px-2 small mb-0">
                                                <i class="bi bi-file-earmark-play-fill"></i> مرفوع حالياً:
                                                <code>{{ basename($lesson->video_url) }}</code>
                                            </div>
                                        @endif

                                        <div class="text-center my-3 fw-bold text-muted">—— أَوْ ——</div>

                                        <label class="form-label fw-bold text-dark">
                                            <i class="bi bi-link-45deg"></i> خيار 2: رابط فيديو خارجي (Video URL)
                                        </label>
                                        <input type="url" name="video_url" class="form-control"
                                            value="{{ old('video_url', isset($lesson) && filter_var($lesson->video_url, FILTER_VALIDATE_URL) ? $lesson->video_url : '') }}"
                                            placeholder="https://example.com/video-link">
                                        <small class="text-muted d-block mt-1">أدخل رابط استضافة خارجي (مثل YouTube أو Vimeo
                                            أو سيرفر سحابي).</small>
                                    </div>
                                </div>

                                {{-- لوحة المقال --}}
                                <div class="tab-pane fade {{ old('lesson_type', $lesson->lesson_type ?? 'video') === 'document' ? 'show active' : '' }}"
                                    id="panel-document" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-info">
                                            <i class="bi bi-textarea-t"></i> محتوى الدرس النصي (Lesson Content)
                                        </label>
                                        <textarea name="text_content" class="form-control" rows="8" placeholder="اكتب محتوى الدرس بالتفصيل هنا...">{{ old('text_content', $lesson->text_content ?? '') }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <hr class="my-4 text-muted">

                            <div class="form-check form-switch card bg-light p-3 my-3 border-0">
                                <div class="ms-4">
                                    <input type="hidden" name="is_preview" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_preview" id="is_preview"
                                        value="1"
                                        {{ old('is_preview', $lesson->is_preview ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="is_preview">إتاحة هذا الدرس كـ
                                        "معاينة مجانية" قبل الاشتراك (Free Preview)</label>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary btn-sm px-4">حفظ ومتابعة</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
