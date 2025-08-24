<?php

namespace App\Http\Requests\SocialNetwork;

use Illuminate\Foundation\Http\FormRequest;

class SocialNetworkRequest extends FormRequest
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
            'name'      => 'sometimes|nullable|string|max:255',
            'url'       => 'sometimes|nullable|url|max:255',
            'is_active' => 'sometimes|in:0,1',
            'icon'      => 'sometimes|nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.string'          => 'الاسم يجب أن يكون نصاً',
            'name.max'             => 'الاسم طويل جداً',
            'url.url'              => 'الرابط غير صحيح',
            'url.max'              => 'الرابط طويل جداً',
            'is_active.boolean'    => 'حقل التفعيل يجب أن يكون صحيح أو خطأ',
            'icon.image'           => 'يجب أن يكون الملف صورة',
            'icon.mimes'           => 'الصورة يجب أن تكون بصيغة: jpg, jpeg, png',
            'icon.max'             => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ];
    }
}
