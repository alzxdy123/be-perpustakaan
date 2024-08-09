<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware(['auth:api', 'owner'])->only('update', 'destroy', 'store');
    }

    public function index(Request $request)
    {
        $categories = Category::with('list_books');

        if($request->input('name')) {
            $categories->where('name', 'like', '%'.$request->input('name').'%');
        }

        $categories->orderBy('created_at', 'desc');

        $categories = $categories->get();

        return response()->json(['data' => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());

        return response()->json(['data' => $category], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        if(!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $category = Category::with('list_books.category')->find($category->id);

        return response()->json(['data' => $category], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        return response()->json(['message' => 'Kategori berhasil diperbarui'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus'], 200);
    }
}
