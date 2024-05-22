<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriesRequest;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoryList = Categories::all();
        return $this->apiResponse('Başarıyla getirildi', true, 200, $categoryList);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriesRequest $request)
    {
        $categories = Categories::create([
            'type' => $request->type
        ]);

        return $this->apiResponse('Kategori başarıyla oluşturuldu', true, 200, $categories);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $category_id)
    {
        $category = Categories::find($category_id);

        if (!$category) {
            return $this->apiResponse('Kategori bulunamadı', false, 404);
        }

        $tests = $category->tests()->get();
        $testCount = $category->tests()->get()->count();
        return $this->apiResponse('Kategori başarıyla getirildi', true, 200, [
            'category' =>
            [
                'id' => $category->id,
                'type' => $category->type,
                'test_count' => $testCount,
            ],
            'tests' => $tests
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categories $test_Categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriesRequest $request, int $category_id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $category_id)
    {
        $category = Categories::find($category_id);

        if (!$category) {
            return $this->apiResponse('Kategori bulunamadı', false, 404);
        }

        $category->delete();
        return $this->apiResponse('Kategori başarıyla silindi', true, 200, $category);
    }
}
