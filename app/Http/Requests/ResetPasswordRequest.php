<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
    // public function rules(): array
    // {
    //     return [
    //         'email' => 'required|email',
    //         'new_password' => [
    //             'required',
    //             'min:8',
    //             'regex:/[A-Z]/',
    //             'regex:/[a-z]/',
    //             'regex:/[0-9]/',
    //             'regex:/[!@#$%^&*()\-_=+{};:,<.>ยง~]/'
    //         ]
    //     ];
    // }

    public function rules()
    {
        return [
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*()\-_=+{};:,<.>ยง~]/'
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Yeni şifre alanı zorunludur.',
            'password.min' => 'Yeni şifre en az 8 karakter içermelidir.',
            'password.regex' => 'Yeni şifre en az bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir.',
            'code.exists' => 'Hatalı kod',
        ];
    }
}
