<?php

namespace App\Http\Controllers;

use App\Models\Tests;
use App\Http\Requests\TestsRequest;
use App\Models\Categories;

class TestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(TestsRequest $request)
    {
        $test = Tests::create([
            'category_id' => $request->category_id
        ]);

        return $this->apiResponse('Test başarıyla oluşturuldu', true, 200, $test);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $test_id)
    {
        $test = Tests::find($test_id);

        if (!$test) {
            return $this->apiResponse('Test bulunamadı', false, 404);
        }

        $questions = $test->question()->with(['options:id,question_id,option_text,is_correct'])->get();

        $formattedQuestions = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'test_id' => $question->test_id,
                'question_text' => $question->question_text,
                'options' => $question->options->map(function ($option) {
                    return [
                        'option_text' => $option->option_text,
                        'is_correct' => $option->is_correct
                    ];
                })
            ];
        });

        $questionCount = $test->question()->count();

        return $this->apiResponse('Teste ait sorular başarıyla getirildi', true, 200, [
            'question_count' => $questionCount,
            'questions' => $formattedQuestions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tests $tests)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TestsRequest $request, int $test_id)
    {
        $test = Tests::find($test_id);
        if (!$test) {
            return $this->apiResponse('Test bulunamadı', false, 404);
        }
        $test->update([
            'category_id' => $request->category_id
        ]);

        return $this->apiResponse('Test başarıyla güncellendi', true, 200, $test);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $test_id)
    {
        $test = Tests::find($test_id);
        if (!$test) {
            return $this->apiResponse('Test bulunamadı', false, 404);
        }

        $test->delete();
        return $this->apiResponse('Test başarıyla silindi', true, 200, $test);
    }
}
