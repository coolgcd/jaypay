<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('p_id')->get();
        // dd($categories); // Debugging line
        return view('admin.category.manage_category', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cat_title' => 'required|string|max:255',
            'cat_desc' => 'nullable|string',
            'cat_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = new Category();
        $category->cat_title = $request->cat_title;
        $category->cat_desc = $request->cat_desc;
        $category->add_date = now()->timestamp;

       if ($request->hasFile('cat_image')) {
    $path = $request->file('cat_image')->store('images/categories', 'public'); // Store in public disk
    $category->cat_image = 'storage/' . $path; // Store relative path
}

        $category->save();
        return redirect()->route('categories.index')->with('message', 'Category added successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cat_title' => 'required|string|max:255',
            'cat_desc' => 'nullable|string',
            'cat_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $category->cat_title = $request->cat_title;
        $category->cat_desc = $request->cat_desc;

        if ($request->hasFile('cat_image')) {
            // Check if the category has an existing image
            if ($category->cat_image) {
                // Delete the existing image from storage
                Storage::delete($category->cat_image); // This will delete the file from storage/app/public/images/categories/
            }

            // Store the new image in the public disk and get the path
            $path = $request->file('cat_image')->store('images/categories', 'public'); // Store in public disk
            $category->cat_image = 'storage/' . $path; // Store the relative path
        }


        $category->save();
        return redirect()->route('categories.index')->with('message', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->cat_image) {
            Storage::delete($category->cat_image);
        }
        $category->delete();
        return redirect()->route('categories.index')->with('message', 'Category deleted successfully.');
    }

    public function stock()
    {
        $categories = Category::orderBy('p_id')->get();
        return view('admin.category.stock', compact('categories'));
    }

    public function delete(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($category->cat_image) {
            Storage::delete($category->cat_image);
        }
        $category->delete();

        return redirect()->route('categories.index')->with('message', 'Record Deleted Successfully.');
    }
}
