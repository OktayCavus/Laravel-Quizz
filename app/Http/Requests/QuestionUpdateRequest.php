<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionUpdateRequest extends FormRequest
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
            'question_text' => 'required|string',
            'options' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'question_text.required' => 'Soru metni zorunlu',
            'question_text.string' => 'Soru metni metin olmalı',
            'options.required' => 'Cevaplar zorunlu',
            'options.array' => 'Cevaplar dizi olmalı',
        ];
    }
}
