<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {

        $categories = Category::all();
        return view('category/index')->with('categories', $categories);
    }

    public function create()
    {
        return view('category/create');
    }

    public function store(Request $request)
    {
        $category = new Category();
        $category->fill($request->all());
        $category->save();
        return redirect('/category/create')->with('success', true);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function findCategoryByName(Request $request)
    {
        $categories = Category::where('name', 'like', "%$request->name%")->get();
       return $categories;
    }
}
