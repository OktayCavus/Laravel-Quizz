<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => 'required|min:3|max:255',
            'username' => 'required|min:3|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'lastname' => 'required|min:3|max:255',
            'password'  => [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*()\-_=+{};:,<.>ยง~]/'
            ]
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Ad alanı boş bırakılamaz',
            'username.string' => 'Ad alanı metin tipinde olmalı',
            'username.min' => 'En az 3 karakterden oluşmalı',
            'username.max' => 'en fazla 255 karakter içerebilir',
            'username.unique' => 'Kullanıcı adı daha önce kullanılmış',
            'name.required' => 'Ad alanı boş bırakılamaz',
            'name.string' => 'Ad alanı metin tipinde olmalı',
            'name.min' => 'En az 3 karakterden oluşmalı',
            'name.max' => 'en fazla 255 karakter içerebilir',
            'lastname.required' => 'Ad alanı boş bırakılamaz',
            'lastname.string' => 'Ad alanı metin tipinde olmalı',
            'lastname.min' => 'En az 3 karakterden oluşmalı',
            'lastname.max' => 'en fazla 255 karakter içerebilir',
            'email.required' => 'E-posta alanı boş bırakılamaz',
            'email.email' => 'E-mail formatında olmalıdır',
            'email.unique' => 'E-posta daha önce kullanılmış',
            'password.required' =>  'Parola alanı boş bırakılamaz',
            'password.min' => 'En az 8 karakterden oluşmalı',
            'password.max' => 'En fazla 255 karakter içerebilir',
            'password.regex' => 'Şifre en az bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir.'
        ];
    }
}
