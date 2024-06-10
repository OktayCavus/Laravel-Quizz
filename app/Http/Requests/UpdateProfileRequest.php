<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        $userId = auth()->id();

        return [
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'name' => 'sometimes|required|min:3',
            'surname' => 'sometimes|required|min:3',
            'username' => [
                'sometimes',
                'required',
                'min:3',
                Rule::unique('users')->ignore($userId),
            ],
            'current_password' => 'sometimes|required',
            'new_password' => [
                'sometimes',
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*()\-_=+{};:,<.>ยง~]/',
            ],
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Bu e-mail başka kullanıcı tarafından kullanılıyor',
            'email.email' => 'E-mail formatında olmalıdır',
            'email.required' => 'E-mail alanı boş bırakılamaz',
            'name.required' => 'İsim alanı boş bırakılamaz',
            'name.min' => 'İsim alanı en az 3 karakterden oluşmalıdır',
            'surname.required' => 'Soyisim alanı boş bırakılamaz',
            'surname.min' => 'Soyisim alanı en az 3 karakterden oluşmalıdır',
            'username.unique' => 'Bu kullanıcı adı başka kullanıcı tarafından kullanılıyor',
            'username.required' => 'Kullanıcı adı alanı boş bırakılamaz',
            'username.min' => 'Kullanıcı adı alanı en az 3 karakterden oluşmalıdır',
            'current_password.required' => 'Şu an kullanılan şifre girilmek zorunda',
            'new_password.required' => 'Parola alanı boş bırakılamaz',
            'new_password.min' => 'En az 8 karakterden oluşmalı',
            'new_password.regex' => 'Şifre en az bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir.',
        ];
    }
}
