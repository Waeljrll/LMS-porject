<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Section;

class QuizPolicy
{
    /**
     * التحقق من صلاحية إنشاء اختبار جديد داخل الـ Section
     */
    public function create(User $user, Section $section): bool
    {
        // إذا كان المسؤول نظام (Admin) فله كامل الصلاحية
        if ($user->role === 'admin') {
            return true;
        }

        // المحاضر يمكنه الإنشاء فقط إذا كان هو صاحب الكورس الذي يتبع له هذا القسم
        return $user->role === 'instructor'
            && $section->course->instructor_id === $user->id;
    }

    /**
     * التحقق من صلاحية عرض أو تعديل اختبار موجود بالفعل
     */
    public function update(User $user, Quiz $quiz): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'instructor'
            && $quiz->section->course->instructor_id === $user->id;
    }
}
