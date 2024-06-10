<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Categories;
use App\Models\Options;
use App\Models\Question;
use App\Models\Tests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    // public function userTestAnswers(int $test_id = 0)
    // {
    //     $user_id = Auth::user()->id;
    //     if ($test_id != 0) {
    //         $tests = Answer::where('user_id', $user_id)
    //             ->where('answers.test_id', $test_id)
    //             ->join('tests', 'answers.test_id', '=', 'tests.id')
    //             ->join('questions', 'answers.question_id', '=', 'questions.id')
    //             ->join('options', 'answers.selected_option_id', '=', 'options.id')
    //             ->join('categories', 'answers.category_id', '=', 'categories.id')
    //             ->get();
    //     } else {
    //         $tests = Answer::where('user_id', $user_id)
    //             ->join('tests', 'answers.test_id', '=', 'tests.id')
    //             ->join('questions', 'answers.question_id', '=', 'questions.id')
    //             ->join('options', 'answers.selected_option_id', '=', 'options.id')
    //             ->join('categories', 'answers.category_id', '=', 'categories.id')
    //             ->get();
    //     }


    //     if ($tests->isEmpty()) {
    //         return $this->apiResponse('Öğrencinin cevapları bulunamadı.', false, 404);
    //     }
    //     $responses = [];
    //     foreach ($tests as $test) {
    //         $response = [
    //             'category_id' => $test->category_id,
    //             'category_name' => $test->type,
    //             'test_id' => $test->test_id,
    //             'question_id' => $test->question_id,
    //             'selected_option_id' => $test->selected_option_id,
    //             'is_correct' => $test->is_correct,
    //             'question_text' => $test->question_text,
    //             'option_text' => $test->option_text,
    //         ];
    //         $responses[] = $response;
    //     }

    //     return $this->apiResponse('Öğrencinin cevapları başarıyla getirildi', true, 200, $responses);
    // }

    public function userTestAnswers(int $category_id = 0)
    {
        $user_id = Auth::user()->id;
        if ($category_id != 0) {
            $tests = Answer::where('user_id', $user_id)
                ->where('answers.category_id', $category_id)
                ->join('tests', 'answers.test_id', '=', 'tests.id')
                ->join('questions', 'answers.question_id', '=', 'questions.id')
                ->join('options', 'answers.selected_option_id', '=', 'options.id')
                ->join('categories', 'answers.category_id', '=', 'categories.id')
                ->get();
        } else {
            $tests = Answer::where('user_id', $user_id)
                ->join('tests', 'answers.test_id', '=', 'tests.id')
                ->join('questions', 'answers.question_id', '=', 'questions.id')
                ->join('options', 'answers.selected_option_id', '=', 'options.id')
                ->join('categories', 'answers.category_id', '=', 'categories.id')
                ->get();
        }


        if ($tests->isEmpty()) {
            return $this->apiResponse('Öğrencinin cevapları bulunamadı.', false, 404);
        }
        $responses = [];
        foreach ($tests as $test) {
            $response = [
                'category_id' => $test->category_id,
                'category_name' => $test->type,
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

    public function userTestCategoryAnswers(int $category_id = 0, int $test_id = 0)
    {
        $user_id = Auth::user()->id;
        $query = Answer::where('user_id', $user_id)
            ->join('tests', 'answers.test_id', '=', 'tests.id')
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->join('options', 'answers.selected_option_id', '=', 'options.id')
            ->join('categories', 'answers.category_id', '=', 'categories.id');

        if ($category_id != 0) {
            $query->where('answers.category_id', $category_id);
        }
        if ($test_id != 0) {
            $query->where('answers.test_id', $test_id);
        }

        $tests = $query->get();

        if ($tests->isEmpty()) {
            return $this->apiResponse('Öğrencinin cevapları bulunamadı.', false, 404);
        }

        $responses = [];
        foreach ($tests as $test) {
            $response = [
                'category_id' => $test->category_id,
                'category_name' => $test->type,
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

    // ! kullanıcının girdiği testlerin categorilerini döndürecek uygulamada onları gösterecez
    public function userTestsWithCategory()
    {
        $user_id = Auth::user()->id;
        $tests = Answer::where('user_id', $user_id)
            ->join('categories', 'answers.category_id', '=', 'categories.id')
            ->select('answers.*', 'categories.id as category_id', 'categories.type as category_type')
            ->get();

        $uniqueCategories = [];
        foreach ($tests as $test) {
            if (!isset($uniqueCategories[$test->category_id])) {
                $uniqueCategories[$test->category_id] = $test->category_type;
            }
        }

        $responses = [];
        foreach ($uniqueCategories as $id => $type) {
            $responses[] = [
                'category_id' => $id,
                'category_name' => $type
            ];
        }

        return $this->apiResponse("Kullanıcının girdiği test kategorileri başarıyla getirildi", true, 200, $responses);
    }

    public function show(int $category_id)
    {
        $userId = auth()->id();

        $tests = Answer::where('user_id', $userId)
            ->where('category_id', $category_id)
            ->join('categories', 'answers.category_id', '=', 'categories.id')
            ->get();

        if ($tests->isEmpty()) {
            return $this->apiResponse('Kategoriye ait test bulunamadı', false, 404);
        }

        $uniqueTests = [];
        foreach ($tests as $test) {
            if (!isset($uniqueTests[$test->test_id])) {
                $uniqueTests[$test->test_id] = $test->category_id;
            }
        }

        $responses = [];
        foreach ($uniqueTests as $id => $category_id) {
            $responses[] = [
                'test_id' => $id,
                'category_id' => $category_id
            ];
        }

        return $this->apiResponse('Kullanıcının girdiği testler başarıyla getirildi', true, 200, $responses);
    }
}
