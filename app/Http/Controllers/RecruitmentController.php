<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use Exception;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function show($recruitmentId)
    {
        try {
            $recruitment = Recruitment::findOrFail($recruitmentId);
            return response()->json([
                'success' => true,
                'recruitment' => $recruitment,
                'employer' => $recruitment->employer,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $recruitment = Recruitment::find($id);
        $recruitment->fill($request->all());
        $recruitment->save();
    }
}
