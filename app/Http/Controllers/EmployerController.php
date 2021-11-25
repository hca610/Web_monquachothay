<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\JobSeeker;
use App\Models\Recruitment;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployerController extends Controller
{
    public function createRecruitment(Request $request)
    {
        try {
            $user = auth()->user();
            $employer = $user->employer;

            $recruitment = new Recruitment();
            $recruitment->fill($request->all());
            $recruitment->employer_id = $employer->employer_id;
            $recruitment->save();

            return response()->json([
                'success' => true,
                'message' => 'Tạo việc làm thành công',
                'data' => $recruitment,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
            ]);
        }
    }

    public function showRecruitments()
    {
        try {
            $user = auth()->user();
            $employer = $user->employer;
            $recruitments = $employer->recruitments;

            return response()->json([
                'success' => true,
                'recruitments' => $recruitments,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function changeStatusOfRecruitment($recruitmentId)
    {
        try {
            $recruitment = Recruitment::findOrFail($recruitmentId);
            if ($recruitment->status == 'opening') {
                $recruitment->status = 'closed';
            } else {
                $recruitment->status = 'opening';
            }

            return response()->json([
                'success' => true,
                'message' => 'Đổi trạng thái thành công',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function changeStatusOfApplication(Request $request)
    {
        try {
            $jobSeeker = JobSeeker::findOrFail($request->job_seeker_id);
            $recruitment = Recruitment::findOrFail($request->recruitment_id);

            $recruitment->jobSeekers()->syncWithPivotValues([$jobSeeker->job_seeker_id], ['type' => $request->status], false);

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getApplications()
    {
        try {
            $employer = auth()->user()->employer;
            $recruitments = $employer->recruitments;
            $collection = new Collection();

            foreach ($recruitments as $recruitment) {
                $collection->push(DB::table('job_seeker_recruitment')
                ->where('recruitment_id', $recruitment->recruitment_id)
                ->where('type', '<>', '')
                ->get());
            }
            return response()->json([
                'success' => true,
                'data' => $collection,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
