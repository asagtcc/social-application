<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'name' => 'required|required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'fcm_token' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
             'name.required'    => 'اسم المستخدم مطلوب',
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique'      => 'البريد الإلكتروني مسجل بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'confirm_password.same' => 'تأكيد كلمة المرور غير مطابق',
        ];
    }
}
