<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name'              => 'sometimes|nullable|string|max:255',
            'posts_per_month'   => 'sometimes|nullable|integer',
            'reels_per_month'   => 'sometimes|nullable|integer',
            'stories_per_month' => 'sometimes|nullable|integer',
            'price'             => 'sometimes|nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الخطة مطلوب.',
            'name.string' => 'اسم الخطة يجب أن يكون نص فقط.',
            'name.max' => 'اسم الخطة لا يجب أن يزيد عن 255 حرف.',

            'posts_per_month.required' => 'عدد البوستات مطلوب.',
            'posts_per_month.integer' => 'عدد البوستات يجب أن يكون رقم صحيح.',

            'reels_per_month.required' => 'عدد الريلز مطلوب.',
            'reels_per_month.integer' => 'عدد الريلز يجب أن يكون رقم صحيح.',

            'stories_per_month.required' => 'عدد الاستوري مطلوب.',
            'stories_per_month.integer' => 'عدد الاستوري يجب أن يكون رقم صحيح.',

            'price.required' => 'سعر الخطة مطلوب.',
            'price.numeric' => 'سعر الخطة يجب أن يكون رقم.',
        ];
    }
}
