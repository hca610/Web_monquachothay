<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use Exception;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $recruitment = new Recruitment();
            $recruitment->fill($request->all());
            $recruitment->save();
        } catch (Exception $e) {
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $recruitment = Recruitment::find($id);
        $recruitment->fill($request->all());
        $recruitment->save();
    }
}
