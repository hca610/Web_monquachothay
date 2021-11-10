<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showAllCategory() {
        $categories = Category::all();
        return view('category')->with('categories', $categories);
    }

    public function addCategory($categoryName) {
        $category = new Category();
        $category->name = $categoryName;
        $category->save();
    }
}
