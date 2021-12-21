<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobSeekerController extends Controller
{
    public function followRecruitment(Request $request)
    {
        try {
            $jobSeeker = auth()->user()->jobSeeker;
            $jobSeeker->recruitments()->syncWithPivotValues([$request->recruitment_id,], ['following' => 1], false);

            return response()->json([
                'success' => true,
                'message' => 'Đã theo dõi công việc',
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
        try {
            $jobSeeker = auth()->user()->jobSeeker;
            $recruitmentId = $request->recruitment_id;
            if ($jobSeeker->recruitments()->where('job_seeker_recruitment.recruitment_id', $recruitmentId)
                ->get()[0]->pivot->type == NULL) {
                $jobSeeker->recruitments()->detach($recruitmentId);
            } else {
                $jobSeeker->recruitments()->syncWithPivotValues([$recruitmentId], ['following' => 0], false);
            }
            return response()->json([
                'success' => true,
                'message' => 'Đã bỏ theo dõi công việc ',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function applyRecruitment(Request $request)
    {
        try {
            $jobSeeker = auth()->user()->jobSeeker;
            $jobSeeker->recruitments()->syncWithPivotValues([$request->recruitment_id,], ['type' => 'pending'], false);


            return response()->json([
                'success' => true,
                'message' => 'Đã gửi yêu cầu',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function unApplyRecruitment(Request $request)
    {
        try {
            $recruitmentId = $request->recruitment_id;
            $jobSeeker = auth()->user()->jobSeeker;
            if ($jobSeeker->recruitments()->where('job_seeker_recruitment.recruitment_id', $recruitmentId)
                ->get()[0]->pivot->following == 0) {
                $jobSeeker->recruitments()->detach($recruitmentId);
            } else {
                $jobSeeker->recruitments()->syncWithPivotValues([$recruitmentId], ['type' => NULL], false);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi yêu cầu',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function interestedRecruitments()
    {
        try {
            $jobSeeker = auth()->user()->jobSeeker;
            $recruitments = DB::table('recruitments')
                ->join('job_seeker_recruitment', 'recruitments.recruitment_id', '=', 'job_seeker_recruitment.recruitment_id')
                ->join('job_seekers', 'job_seekers.job_seeker_id', '=', 'job_seeker_recruitment.job_seeker_id')
                ->join('employers', 'employers.employer_id', '=', 'recruitments.employer_id')
                ->join('users', 'users.user_id', '=', 'employers.user_id')
                ->where('job_seeker_recruitment.job_seeker_id', $jobSeeker->job_seeker_id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $recruitments,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
