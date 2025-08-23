<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنظمة مطلوب.',
            'name.string' => 'اسم المنظمة يجب أن يكون نصًا.',
            'name.max' => 'اسم المنظمة لا يجب أن يزيد عن 255 حرفًا.',
            'description.string' => 'الوصف يجب أن يكون نصًا.',
        ];
    }
}
