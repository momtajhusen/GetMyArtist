<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;


class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::whereNull('parent_id')->with('subcategories')->get();
        return view('AdminPanel.Categories.categories', compact('categories'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
            'status'      => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        
    
        $data = $request->all();
    
        $data['created_by'] = Auth::id();
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
    
        Categories::create($data);
    
        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }
    

    public function update(Request $request, $id)
    {
        $category = Categories::findOrFail($id);
    
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
            'status'      => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);        
    
        $isParentChanging = $category->parent_id != $request->parent_id;
        if ($isParentChanging && $category->subcategories()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cannot change parent category. This category has subcategories.');
        }
    
        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
    
        $category->update($data);
    
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }
    
    
    public function destroy($id)
    {
        $category = Categories::findOrFail($id);

        if ($category->subcategories()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete this category. It has subcategories.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
