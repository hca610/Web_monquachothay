<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create()
    {
        //
    }

    // public function store(Request $request)
    // {
    //     $category = new Category();
    //     $category->fill($request->all());
    //     $category->save();
    // }

    public function store(Request $request)
    {
        $category = new Category();
        $category->fill($request->all());
        $category->save();
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

    public function search(Request $request)
    {
        $categories = Category::where('name', 'like', "%$request->name%")->get();
        return $categories;
    }
}
