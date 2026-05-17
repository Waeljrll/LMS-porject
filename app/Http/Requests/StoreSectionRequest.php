<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'برجاء إدخال عنوان الفصل (Section Title).',
            'title.max' => 'عنوان الفصل طويل جداً، بحد أقصى 255 حرف.',
        ];
    }
}
