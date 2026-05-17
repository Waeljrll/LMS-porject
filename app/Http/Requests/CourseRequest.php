<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules($courseId = null): array
    {
        return [
            'title'             => 'required|string|max:255|unique:courses,title,' . $courseId,
            'short_description' => 'required|string|max:150',
            'description'       => 'required|string|min:20',
            'category_id'       => 'required|exists:categories,id',
            'language'          => 'required|string|max:50',
            'instructor_id'     => 'required|exists:users,id',
            'difficulty_level'  => 'required|in:beginner,intermediate,advanced',
            'status'            => 'required|in:draft,published',

            'thumbnail'         => $courseId ? 'nullable|image|max:2048' : 'required|image|max:2048',

            'objectives'        => 'required|array|min:3',
            'objectives.*'      => 'required|string|min:5|max:255',
            'requirements'      => 'nullable|string',
            'who_is_it_for'     => 'nullable|string',
            'duration_hours'    => 'required|integer|min:0',
            'duration_minutes'  => 'required|integer|min:0|max:59',
            'price'             => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'             => 'اسم الكورس مطلوب ولا يمكن تركه فارغاً.',
            'title.unique'               => 'اسم الكورس هذا مسجل مسبقاً، اختر اسماً آخر.',
            'short_description.required' => 'الوصف المختصر مطلوب لظهوره في الكروت.',
            'description.min'            => 'يجب أن يحتوي الوصف الكامل على 20 حرفاً على الأقل.',
            'category_id.required'       => 'الرجاء اختيار القسم التابع له الكورس.',
            'instructor_id.required'     => 'يجب تحديد المحاضر المسؤول عن الكورس.',
            'difficulty_level.required'  => 'يرجى تحديد مستوى الصعوبة.',
            'thumbnail.required'         => 'صورة الكورس الخلفية (Thumbnail) إجبارية عند الإنشاء.',
            'thumbnail.max'              => 'حجم الصورة كبير جداً، الحد الأقصى المسموح به هو 2 ميجابايت.',

            'objectives.min'             => 'يجب إضافة 3 أهداف تعليمية على الأقل للكورس.',
            'objectives.*.required'      => 'هذا الحقل مطلوب، لا تترك هدفاً فارغاً أو قم بحذفه.',
            'objectives.*.min'           => 'يجب أن يكون نص الهدف واضحاً (5 أحرف على الأقل).',

            // رسائل الوقت والسعر
            'duration_minutes.max'       => 'الدقائق لا يمكن أن تتخطى 59 دقيقة (قم بزيادة الساعات بدلاً من ذلك).',
            'price.required'             => 'يرجى تحديد سعر الكورس أو كتابة 0 ليصبح مجانياً.',
        ];
    }
}
