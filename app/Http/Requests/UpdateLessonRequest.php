<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'lesson_type' => 'required|in:video,document',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:51200',
            'video_url' => 'nullable|url',
            'is_preview' => 'nullable|boolean',
        ];
    }
}
