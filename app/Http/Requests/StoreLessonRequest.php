<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'lesson_type' => 'required|in:video,article',
            'video_url' => $this->lesson_type === 'video' ? 'required|url' : 'nullable|url',
            'content' => $this->lesson_type === 'article' ? 'required|string' : 'nullable|string',
            'duration_minutes' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الدرس مطلوب.',
            'lesson_type.required' => 'يجب تحديد نوع الدرس (فيديو أم مقال).',
            'video_url.required' => 'رابط الفيديو مطلوب عندما يكون نوع الدرس فيديو.',
            'video_url.url' => 'برجاء إدخال رابط فيديو صحيح (URL).',
            'content.required' => 'محتوى المقال مطلوب عندما يكون نوع الدرس مقالاً.',
            'duration_minutes.required' => 'مدة الدرس مطلوبة.',
            'duration_minutes.integer' => 'يجب أن تكون المدة أرقاماً فقط.',
        ];
    }
}
