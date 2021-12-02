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

use App\Http\Controllers\NotificationController; /// Muon dung NotificationController thi phai them dong nay

class EmployerController extends Controller
{
    public function createRecruitment(Request $request)
    {
        // Day la vi du, ong tu sua nhe
        echo NotificationController::create([ // May cai nay thich thi dien, khong dien thi no se ra gia tri mac dinh
            'title' => 'normal',
            'status' => 'unseen',
            'detail' => 'auto notification after create Recruitment id 87',
            'receiver_id' => 1,
        ]); /// create la function chi duoc truy cap o cap do default tuc la phai cung folder moi cho access, 
            /// neu thay kho khan qua thi ong cu viec chinh len public
            /// Nhung trong truong hop nay default la du, de public se khong an toan do create ko co check auth.
        return;
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
