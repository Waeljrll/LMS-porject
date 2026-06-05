@if ($lesson->lesson_type === 'video')
    @if ($lesson->youtube_id)
        <div class="ratio ratio-16x9 w-100 h-100">
            <iframe src="https://www.youtube.com/embed/{{ $lesson->youtube_id }}" allowfullscreen></iframe>
        </div>
    @else
        <video class="w-100 h-100" controls controlsList="nodownload">
            <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
            متصفحك لا يدعم تشغيل الفيديو.
        </video>
    @endif
@else
    <div class="text-center p-5 text-white">
        <i class="fas fa-file-alt fa-5x text-secondary mb-3"></i>
        <h4>محتوى نصي</h4>
        <p>يمكنك قراءة المحتوى التعليمي أدناه.</p>
    </div>
@endif
