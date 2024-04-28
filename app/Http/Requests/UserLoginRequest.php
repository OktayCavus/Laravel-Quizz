<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
            'email' => 'required_without:username|email',
            'username' => 'required_without:email',
            'password' => 'required|min:8|max:255'
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'E-posta alanı boş bırakılamaz',
            'email.email' => 'E-mail formatında olmalıdır',
            'email.unique' => 'E-posta daha önce kullanılmış',
            'username.required' => 'Kullanıcı adı alanı boş bırakılamaz',
            'username.unique' => 'Kullanıcı adı daha önce kullanılmış',
            'password.required' =>  'Parola alanı boş bırakılamaz',
            'password.min' => 'En az 8 karakterden oluşmalı',
            'password.max' => 'En fazla 255 karakter içerebilir',
            'email.required_without' => 'E-posta veya kullanıcı adı alanı boş bırakılamaz'
        ];
    }
}
