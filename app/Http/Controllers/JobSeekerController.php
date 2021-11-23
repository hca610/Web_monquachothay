<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\JobSeeker;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobSeekerController extends Controller
{
    // TODO: them phan  follow, cong viec cac thu
    public function followRecruitment(Request $request)
    {
        $jobSeeker = auth()->user()->jobSeeker;
        // return $jobSeeker;
        
        try {
            $jobSeeker->recruitments->pivot->job_seeker_id = $jobSeeker->job_seeker_id;
            $jobSeeker->recruitments->pivot->recruitment_id = $request->recruitment_id;
            $jobSeeker->recruitments->pivot->following = 1;
            $jobSeeker->recruitments->pivot->save();
            return response()->json([
                'success' => true,
                // 'message' => ''
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function unfollowRecruitment(Request $request)
    {
        $jobSeeker = auth()->user()->jobSeeker;
        $jobSeeker->recruitments->pivot->job_seeker_id = $jobSeeker->job_seeker_id;
        $jobSeeker->recruitments->pivot->recruitment_id = $request->recruitment_id;
        $jobSeeker->recruitments->pivot->following = 0;
        $jobSeeker->recruitments->pivot->save();
    }
}
