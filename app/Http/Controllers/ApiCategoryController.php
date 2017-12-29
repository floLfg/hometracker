<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class ApiCategoryController extends Controller
{
    public function index()
    {
    	return response()->json(Category::all());
    }

    public function update($id, CategoryRequest $request)
    {
    	$category = Category::find($id);
    	$category->update($request->json()->all());

    	return response()->json($category);
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->json()->all());
        return response()->json($category);
    }

    public function destroy($id)
    {
        Category::where('id', $id)->delete();
    }
}
