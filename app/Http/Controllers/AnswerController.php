<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Options;
use App\Models\Question;
use App\Models\Tests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function submitAnswer(Request $request)
    {
        $user = Auth::user();

        $testId = $request->test_id;
        $categoryId = $request->category_id;
        $answersData = $request->answers;

        $responses = [];

        foreach ($answersData as $answerData) {
            $questionId = $answerData['question_id'];
            $selectedOptionId = $answerData['selected_option_id'];

            $question = Question::with('options')->find($questionId);
            if (!$question) {
                return $this->apiResponse('Soru bulunamadı', false, 404);
            }

            $correctOption = $question->options->where('is_correct', 1)->first();

            $isCorrect = ($selectedOptionId == $correctOption->id) ? 1 : 0;

            $answer = Answer::create([
                'user_id' => $user->id,
                'category_id' => $categoryId,
                'test_id' => $testId,
                'question_id' => $questionId,
                'selected_option_id' => $selectedOptionId,
                'is_correct' => $isCorrect,
            ]);

            $responses[] = [
                'question_id' => $questionId,
                'selected_option_id' => $selectedOptionId,
                'is_correct' => $isCorrect,
                'answer_id' => $answer->id
            ];
        }

        return $this->apiResponse('Tüm cevaplar başarıyla kaydedildi', true, 200, $responses);
    }

    public function userTestAnswers(int $test_id = 0)
    {
        $user_id = Auth::user()->id;
        if ($test_id != 0) {
            $tests = Answer::where('user_id', $user_id)
                ->where('answers.test_id', $test_id)
                ->join('tests', 'answers.test_id', '=', 'tests.id')
                ->join('questions', 'answers.question_id', '=', 'questions.id')
                ->join('options', 'answers.selected_option_id', '=', 'options.id')
                ->get();
        } else {
            $tests = Answer::where('user_id', $user_id)
                ->join('tests', 'answers.test_id', '=', 'tests.id')
                ->join('questions', 'answers.question_id', '=', 'questions.id')
                ->join('options', 'answers.selected_option_id', '=', 'options.id')
                ->get();
        }


        if ($tests->isEmpty()) {
            return $this->apiResponse('Öğrencinin cevapları bulunamadı.', false, 404);
        }
        $responses = [];
        foreach ($tests as $test) {
            $response = [
                'user_id' => $test->user_id,
                'category_id' => $test->category_id,
                'test_id' => $test->test_id,
                'question_id' => $test->question_id,
                'selected_option_id' => $test->selected_option_id,
                'is_correct' => $test->is_correct,
                'question_text' => $test->question_text,
                'option_text' => $test->option_text,
            ];
            $responses[] = $response;
        }

        return $this->apiResponse('Öğrencinin cevapları başarıyla getirildi', true, 200, $responses);
    }
}
