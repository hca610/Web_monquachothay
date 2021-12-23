<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Recruitment;
use Illuminate\Database\Eloquent\Collection;

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
            $recruitment = Recruitment::find($request->recruitment_id);

            NotificationController::create([
                'title' => 'Có ứng viên mới',
                'status' => 'unseen',
                'detail' => 'Đã có ứng viên mới tại việc làm '.$recruitment->job_name,
                'receiver_id' => $recruitment->employer->user->user_id,
            ]);
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
            $collection = new Collection();
            if (auth()->user()->role != 'jobseeker')
                throw new Exception('Bạn không phải là người tìm việc');
            $jobSeeker = auth()->user()->jobSeeker;

            $recruitments = DB::table('recruitments')
                ->join('job_seeker_recruitment', 'job_seeker_recruitment.recruitment_id', '=', 'recruitments.recruitment_id')
                ->get();

            foreach ($recruitments as $recruitment) {
                $pivotObject = $this->checkStatusOfRecruitmentAsJobSeeker($jobSeeker, $recruitment);

                if (sizeof($pivotObject) > 0) {
                    $following = $pivotObject[0]->following;
                    $applicationStatus = $pivotObject[0]->type;

                    // Need this line because recruitments selected by table don't have function like
                    // recruitments selected by model query
                    $recruitment = Recruitment::find($recruitment->recruitment_id);

                    $collection->push([
                        'recruitment' => $recruitment,
                        'employer' => $recruitment->employer,
                        'user' => $recruitment->employer->user,
                        'isFollowing' => $following,
                        'applicationStatus' => $applicationStatus,
                    ]);
                }
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
