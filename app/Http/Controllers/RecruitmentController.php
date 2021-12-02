<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecruitmentController extends Controller
{
    public function showAllRecruitment()
    {
        if (auth()->user() != NULL)
            return response()->json($this->showAllRecruitmentAsJobSeeker());
        else
            return response()->json($this->showAllRecruitmentAsGuest());
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

    protected function showAllRecruitmentAsGuest()
    {
        $collection = new Collection();

        $recruitments = Recruitment::all()->sortByDesc('created_at');
        foreach ($recruitments as $recruitment) {
            $collection->push([
                'recruitment' => $recruitment,
                'emloyer' => $recruitment->employer,
            ]);
        }

        return $collection;
    }

    protected function showAllRecruitmentAsJobSeeker()
    {
        try {
            $collection = new Collection();
            $jobSeeker = auth()->user()->jobSeeker;

            $recruitments = Recruitment::all()->sortByDesc('created_at');
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
