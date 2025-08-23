<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
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
            'user_id'   => 'nullable|exists:users,id',
            'name'      => 'nullable|required_without:user_id|string|max:255',
            'email'     => 'nullable|required_without:user_id|email|unique:users,email',
            'password'  => 'nullable|required_without:user_id|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists'            => 'المستخدم المحدد غير موجود.',
            'name.required_without'     => 'الاسم مطلوب عند عدم اختيار مستخدم.',
            'name.string'               => 'الاسم يجب أن يكون نصًا صحيحًا.',
            'name.max'                  => 'الاسم يجب ألا يزيد عن 255 حرفًا.',
            'email.required_without'    => 'البريد الإلكتروني مطلوب عند عدم اختيار مستخدم.',
            'email.email'               => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique'              => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'password.required_without' => 'كلمة المرور مطلوبة عند عدم اختيار مستخدم.',
            'password.string'           => 'كلمة المرور يجب أن تكون نصًا.',
            'password.min'              => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف.',
        ];
    }
    
}
