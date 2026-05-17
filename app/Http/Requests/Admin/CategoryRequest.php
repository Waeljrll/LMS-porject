<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $categoryId = $this->category ? $this->category->id : null;

        return [
            'name' => [
                'required',
                'max:255',
                'unique:categories,name,' . $categoryId,
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم القسم مطلوب ولا يمكن تركه فارغاً.',
            'name.unique' => 'هذا القسم موجود بالفعل، اختر اسماً آخر.',
            'name.max' => 'اسم القسم طويل جداً، الحد الأقصى 255 حرف.',
        ];
    }
}

