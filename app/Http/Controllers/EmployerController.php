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

use App\Http\Controllers\NotificationController;

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
                'error' => $e->getMessage(),
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

    public function updateRecruitment(Request $request, $id)
    {
        try {
            $recruitment = Recruitment::find($id);
            $recruitment->fill($request->all());
            $recruitment->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
            ]);
        }
    }

    public function changeStatusOfApplication(Request $request)
    {
        try {
            $jobSeeker = JobSeeker::findOrFail($request->job_seeker_id);
            $recruitment = Recruitment::findOrFail($request->recruitment_id);

            $recruitment->jobSeekers()->syncWithPivotValues(
                [$jobSeeker->job_seeker_id],
                ['type' => $request->status],
                false
            );

            switch ($request->status) {
                case 'reviewed':
                    $titleNotification = 'Hồ sơ của bạn đã được duyệt';
                    $detailNotification = 'Hồ sơ của bạn đã được duyệt ở công việc ' . $recruitment->job_name;
                    break;
                case 'hired':
                    $titleNotification = 'Bạn đã được nhận vào làm!';
                    $detailNotification = 'Bạn đã được nhận vào làm ở công việc ' . $recruitment->job_name;
                    break;
                case 'rejected':
                    $titleNotification = 'Hồ sơ của bạn đã bị từ chối';
                    $detailNotification = 'Hồ sơ của bạn đã bị từ chối ở công việc ' . $recruitment->job_name;
                    break;
            }

            NotificationController::create([
                'title' => $titleNotification,
                'status' => 'unseen',
                'detail' => $detailNotification,
                'receiver_id' => $jobSeeker->job_seeker_id,
            ]);

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
            $listApplication = new Collection();

            foreach ($recruitments as $recruitment) {
                $listApplication->push(DB::table('job_seeker_recruitment')
                    ->join('job_seekers', 'job_seekers.job_seeker_id', '=', 'job_seeker_recruitment.job_seeker_id')
                    ->join('users', 'users.user_id', 'job_seekers.user_id')
                    ->where('recruitment_id', $recruitment->recruitment_id)
                    ->where('type', '<>', '')
                    ->get());
            }

            return response()->json([
                'success' => true,
                'data' => $listApplication,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
