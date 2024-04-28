<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'email' => 'sometimes|required|unique:users|email',
            'name' => 'sometimes|required|min:3',
            'surname' => 'sometimes|required|min:3',
            'username' => 'sometimes|required|unique:users|min:3',
            'current_password' => 'sometimes|required',
            'new_password' => [
                'sometimes',
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*()\-_=+{};:,<.>ยง~]/'
            ]

        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Bu e-mail başka kullanıcı tarafından kullanılıyor',
            'email.email' => 'E-mail formatında olmalıdır',
            'email.required' => 'E-mail alanı boş bırakılamaz',
            'email.sometimes' => 'email - sometimes',
            'name.sometimes' => 'name - sometimes',
            'name.required' => 'İsim alanı boş bırakılamaz',
            'name.min' => 'İsim alanı en az 3 karakterden oluşmalıdır',
            'surname.sometimes' => 'surname - sometimes',
            'surname.required' => 'Soyisim alanı boş bırakılamaz',
            'surname.min' => 'Soyisim alanı en az 3 karakterden oluşmalıdır',
            'username.unique' => 'Bu kullanıcı adı başka kullanıcı tarafından kullanılıyor',
            'username.required' => 'Kullanıcı adı alanı boş bırakılamaz',
            'username.sometimes' => 'username - sometimes',
            'username.min' => 'Kullanıcı adı alanı en az 3 karakterden oluşmalıdır',
            'current_password.sometimes' => 'current_password - sometimes',
            'current_password.required' => 'Şu an kullanılan şifre girilmek zorunda',
            'new_password.sometimes' =>  'new_password - sometimes',
            'new_password.required' =>  'Parola alanı boş bırakılamaz',
            'new_password.min' => 'En az 8 karakterden oluşmalı',
            'new_password.regex' => 'Şifre en az bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir.'

        ];
    }
}
