<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Http\Requests\QuestionUpdateRequest;
use App\Models\Options;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $test_id = request('test_id', 0);
        $limit = request('limit', 0);
        $page = request('page', 0);

        $query = Question::with(['options:id,question_id,option_text,is_correct']);

        // tests tablosunu question tablosuyla birleştir ve category_id'yi seç
        $query->select('questions.*', 'tests.category_id as test_category_id')
            ->join('tests', 'questions.test_id', '=', 'tests.id');

        if ($test_id != 0) {
            $query->where('test_id', $test_id);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        if ($page > 0) {
            $offset = ($page - 1) * $limit;
            $query->offset($offset);
        }

        $questionList = $query->get();

        if ($questionList->isEmpty()) {
            return $this->apiResponse('Soru bulunamadı.', false, 404);
        }

        $formattedQuestions = $questionList->map(function ($question) {
            return [
                'id' => $question->id,
                'test_id' => $question->test_id,
                'question_text' => $question->question_text,
                'question_id' => $question->id,
                'test_category_id' => $question->test_category_id,
                'options' => $question->options->map(function ($option) {
                    return [
                        'option_text' => $option->option_text,
                        'is_correct' => $option->is_correct,
                        'option_id' => $option->id,
                    ];
                })
            ];
        });

        return $this->apiResponse('Soru ve cevaplar başarıyla getirildi', true, 200, $formattedQuestions);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(QuestionRequest $request)
    // {
    //     $question = Question::create([
    //         'test_id' => $request->test_id,
    //         'question_text' => $request->question_text
    //     ]);

    //     $questionId = $question->id;

    //     $options = [];

    //     foreach ($request->options as $optionData) {
    //         $option = Options::create([
    //             'question_id' => $questionId,
    //             'option_text' => $optionData['option_text'],
    //             'is_correct' => $optionData['is_correct']
    //         ]);

    //         $options[] = $option;
    //     }

    //     $responseData = [
    //         'test' => $question,
    //         'options' => $options
    //     ];

    //     return $this->apiResponse('Soru ve cevaplar başarıyla oluşturuldu', true, 200, $responseData);
    // }
    public function store(QuestionRequest $request)
    {
        $responseData = [];

        foreach ($request->questions as $questionData) {
            $question = Question::create([
                'test_id' => $questionData['test_id'],
                'question_text' => $questionData['question_text']
            ]);

            $options = [];

            foreach ($questionData['options'] as $optionData) {
                $option = Options::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $optionData['is_correct']
                ]);

                $options[] = $option;
            }

            $responseData[] = [
                'test' => $question,
                'options' => $options
            ];
        }

        return $this->apiResponse('Sorular başarıyla oluşturuldu', true, 200, $responseData);
    }

    /**
     * Display the specified resource.
     */

    public function show(int $question_id)
    {
        $question = Question::find($question_id);

        if (!$question) {
            return $this->apiResponse('Soru bulunamadı', false, 404);
        }

        $questionData = [
            'question_text' => $question->question_text,
            'options' => $question->options()->select('id', 'question_id', 'option_text', 'is_correct')->get(),
        ];

        return $this->apiResponse('Seçilen soru ve cevaplar getirildi', true, 200, $questionData);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(QuestionUpdateRequest $request, int $question_id)
    {
        $selectedQuestion = Question::find($question_id);

        if (!$selectedQuestion) {
            return $this->apiResponse('Soru bulunamadı', false, 404);
        }

        $updateData = [];

        if ($request->has('question_text')) {
            $updateData['question_text'] = $request->input('question_text');
        }

        if ($request->has('options')) {
            $options = $request->input('options');
            $optionsToUpdate = [];

            foreach ($options as $option) {
                $optionId = $option['id'];
                $updatedOptionData = [];

                if (isset($option['option_text'])) {
                    $updatedOptionData['option_text'] = $option['option_text'];
                }

                if (isset($option['is_correct'])) {
                    $updatedOptionData['is_correct'] = $option['is_correct'];
                }

                $optionsToUpdate[$optionId] = $updatedOptionData;
            }

            foreach ($optionsToUpdate as $optionId => $optionData) {
                $selectedQuestion->options()->where('id', $optionId)->update($optionData);
            }
        }

        $selectedQuestion->update($updateData);

        return $this->apiResponse('Soru başarıyla güncellendi', true, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $question_id)
    {
        $selectedQuestion = Question::find($question_id);

        if (!$selectedQuestion) {
            return $this->apiResponse('Soru bulunamadı', false, 404);
        }

        $selectedQuestion->options()->delete();
        $selectedQuestion->delete();

        return $this->apiResponse('Soru ve bağlı seçenekler başarıyla silindi.', true, 200);
    }
}
