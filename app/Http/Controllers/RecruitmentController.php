<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecruitmentController extends Controller
{
    public function showAllRecruitment()
    {
        return DB::table('recruitments')
            ->join('employers', 'recruitments.employer_id', '=', 'employers.employer_id')
            ->get();
    }

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

   
}
