<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|nullable|string|max:255',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'الاسم يجب أن يكون نصاً',
            'name.max' => 'الاسم طويل جداً',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل',
            'password.string' => 'كلمة المرور يجب أن تكون نصاً',
            'password.max' => 'كلمة المرور طويلة جداً',

            'photo.image' => 'يجب أن يكون الملف صورة',
            'photo.mimes' => 'الصورة يجب أن تكون بصيغة: jpg, jpeg, png',
            'photo.max'   => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ];
    }
}
