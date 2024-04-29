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
            'test_id' => 'required',
            'question_text' => 'required|string',
            'options' => 'required|array',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => [
                'required',
                Rule::in(['0', '1']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'test_id.required' => 'Eklenecek testi belirtmek zorundasınız',
            'question_text.required' => 'Soru metni girilmek zorunda',
            'question_text.string' => 'Soru metni alanı metin olmalıdır.',
            'options.required' => 'Seçenekler belirtilmelidir.',
            'options.array' => 'Seçenekler dizi olarak gelmeli.',
            'options.*.option_text.required' => 'Her seçeneğin metni girilmelidir.',
            'options.*.option_text.string' => 'Seçenek metin olmalıdır.',
            'options.*.is_correct.required' => 'Her seçeneğin doğruluk bilgisi belirtilmelidir.',
            'options.*.is_correct.in' => 'Her seçeneğin doğruluk bilgisi 0 veya 1 olmalıdır.',
        ];
    }
}
