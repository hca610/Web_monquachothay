<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $category = new Category();
        $category->fill($request->all());
        $category->save();
    }

   public function search(Request $request)
    {
        $categories = Category::where('name', 'like', "%$request->name%")->get();
        return $categories;
    }
}
