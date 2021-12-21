<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecruitmentController extends Controller
{
    public function showAllRecruitment(Request $request)
    {
        if (auth()->user() != NULL)
            return response()->json($this->showAllRecruitmentAsJobSeeker($request));
        else
            return response()->json($this->showAllRecruitmentAsGuest($request));
    }

    public function show($recruitmentId)
    {
        try {
            $recruitment = Recruitment::findOrFail($recruitmentId);
            $following = 0;
            $applicationStatus = NULL;

            if (auth()->user() != NULL) {
                $jobSeeker = auth()->user()->jobSeeker;

                $pivotObject = $this->checkStatusOfRecruitmentAsJobSeeker($jobSeeker, $recruitment);

                if (sizeof($pivotObject) > 0) {
                    $following = $pivotObject[0]->following;
                    $applicationStatus = $pivotObject[0]->type;
                } else {
                    $following = 0;
                    $applicationStatus = NULL;
                }
            }

            return response()->json([
                'success' => true,
                'recruitment' => $recruitment,
                'employer' => $recruitment->employer,
                'user' => $recruitment->employer->user,
                'isFollowing' => $following,
                'applicationStatus' => $applicationStatus,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi',
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function showAllRecruitmentAsGuest(Request $request)
    {
        if ($request->created_at == NULL)
            $request->created_at = '1000-01-01';
        if ($request->min_salary == NULL)
            $request->min_salary = 0;

        $collection = new Collection();

        $recruitments = Recruitment::orderByDesc('created_at')
            ->where('address', 'like', '%' . $request->address . '%')
            ->where('category', 'like', '%' . $request->category . '%')
            ->where('job_name', 'like', '%' . $request->job_name . '%')
            ->where('min_salary', '>', $request->min_salary)
            ->where('created_at', '>', $request->created_at)
            ->where('status', '=', 'opening')
            ->get();

        foreach ($recruitments as $recruitment) {
            if ($recruitment->employer->user->status == 'active')
                $collection->push([
                    'recruitment' => $recruitment,
                    'emloyer' => $recruitment->employer,
                    'user' => $recruitment->employer->user,
                ]);
        }

        return $collection;
    }

    protected function showAllRecruitmentAsJobSeeker(Request $request)
    {
        try {
            if ($request->created_at == NULL)
                $request->created_at = '1000-01-01';
            if ($request->min_salary == NULL)
                $request->min_salary = 0;

            $collection = new Collection();
            $jobSeeker = auth()->user()->jobSeeker;

            $recruitments = Recruitment::orderByDesc('created_at')
                ->where('address', 'like', '%' . $request->address . '%')
                ->where('category', 'like', '%' . $request->category . '%')
                ->where('job_name', 'like', '%' . $request->job_name . '%')
                ->where('min_salary', '>', $request->min_salary)
                ->where('created_at', '>', $request->created_at)
                ->where('status', '=', 'opening')
                ->get();

            foreach ($recruitments as $recruitment) {
                $pivotObject = $this->checkStatusOfRecruitmentAsJobSeeker($jobSeeker, $recruitment);

                if (sizeof($pivotObject) > 0) {
                    $following = $pivotObject[0]->following;
                    $applicationStatus = $pivotObject[0]->type;
                } else {
                    $following = 0;
                    $applicationStatus = NULL;
                }

                $collection->push([
                    'recruitment' => $recruitment,
                    'emloyer' => $recruitment->employer,
                    'isFollowing' => $following,
                    'applicationStatus' => $applicationStatus,
                ]);
            }

            return $collection;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function checkStatusOfRecruitmentAsJobSeeker($jobSeeker, $recruitment)
    {
        try {
            $pivotObject = DB::table('job_seeker_recruitment')
                ->where('job_seeker_id', $jobSeeker->job_seeker_id)
                ->where('recruitment_id', $recruitment->recruitment_id)
                ->get();

            return $pivotObject;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
