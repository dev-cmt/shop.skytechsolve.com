<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('backEnd.admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);
        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request)
    {
        $category = Category::find($request->id);

        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);
        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
