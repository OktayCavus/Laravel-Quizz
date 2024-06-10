<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'questions' => 'required|array', // Birden fazla soru için dizi olmalı
            'questions.*.test_id' => 'required',
            'questions.*.question_text' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.options.*.is_correct' => [
                'required',
                Rule::in(['0', '1']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'questions.required' => 'Soru bilgileri belirtilmelidir.', // Düzeltildi
            'questions.array' => 'Sorular dizi olarak gelmeli.', // Düzeltildi
            'questions.*.test_id.required' => 'Her sorunun bir testi olmalıdır.', // Düzeltildi
            'questions.*.question_text.required' => 'Her sorunun metni girilmelidir.', // Düzeltildi
            'questions.*.question_text.string' => 'Her sorunun metni bir metin olmalıdır.', // Düzeltildi
            'questions.*.options.required' => 'Her sorunun seçenekleri belirtilmelidir.', // Düzeltildi
            'questions.*.options.array' => 'Her sorunun seçenekleri dizi olarak gelmeli.', // Düzeltildi
            'questions.*.options.*.option_text.required' => 'Her seçeneğin metni girilmelidir.',
            'questions.*.options.*.option_text.string' => 'Seçenek metin olmalıdır.',
            'questions.*.options.*.is_correct.required' => 'Her seçeneğin doğruluk bilgisi belirtilmelidir.',
            'questions.*.options.*.is_correct.in' => 'Her seçeneğin doğruluk bilgisi 0 veya 1 olmalıdır.',
        ];
    }
}
