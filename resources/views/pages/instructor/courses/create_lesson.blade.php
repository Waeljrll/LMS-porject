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
                        <form action="{{ route($role . '.lessons.store', $section->id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">عنوان الدرس *</label>
                                <input type="text" name="title" class="form-control" required
                                    placeholder="مثال: إعداد بيئة العمل والـ Composer">
                                @error('title')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">نوع الدرس *</label>
                                <select name="lesson_type" class="form-select" required>
                                    <option value="video">فيديو (Video Lesson)</option>
                                    <option value="article">مقال / نص (Article Lesson)</option>
                                </select>
                            </div>

                            <div class="card p-3 mb-3 bg-light border-0">
                                <h6 class="text-muted mb-3 font-weight-bold">محتوى الدرس (املاً الحقل المناسب لنوع الدرس):
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">رابط الفيديو (في حالة درس الفيديو)</label>
                                    <input type="url" name="video_url" class="form-control"
                                        placeholder="https://youtube.com/...">
                                    @error('video_url')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">المحتوى النصي (في حالة الدرس المقالي)</label>
                                    <textarea name="content" class="form-control" rows="5" placeholder="اكتب الشرح المقالي هنا..."></textarea>
                                    @error('content')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">المدة بالدقائق *</label>
                                    <input type="number" name="duration_minutes" class="form-control" value="10"
                                        min="0" required>
                                    @error('duration_minutes')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" name="is_preview" value="1" class="form-check-input"
                                    id="isPreview">
                                <label class="form-check-label text-success font-weight-bold" for="isPreview">
                                    اجعل هذا الدرس متاحاً كـ معاينة مجانية قبل الاشتراك (Free Preview)
                                </label>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route($role . '.courses.content', $course->id) }}"
                                    class="btn btn-secondary btn-sm me-2">إلغاء وعودة</a>
                                <button type="submit" class="btn btn-success btn-sm">حفظ الدرس ونشره</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
