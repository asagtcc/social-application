<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'email'    => 'required|string|email|max:255|unique:users,email,' . ($this->user()->id ?? 'NULL'),
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

        ];
    }

        public function messages(): array
    {
        return [
            'name.required'   => 'الاسم مطلوب.',
            'name.string'     => 'الاسم يجب أن يكون نصًا صحيحًا.',
            'name.max'        => 'الاسم يجب ألا يزيد عن 255 حرفًا.',

            'email.required'  => 'البريد الإلكتروني مطلوب.',
            'email.email'     => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique'    => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'email.max'       => 'البريد الإلكتروني يجب ألا يزيد عن 255 حرفًا.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min'      => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',

            'photo.image'  => 'الملف يجب أن يكون صورة.',
            'photo.mimes'  => 'الصورة يجب أن تكون بصيغة: jpeg, png, jpg, gif, svg, webp.',
            'photo.max'    => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
