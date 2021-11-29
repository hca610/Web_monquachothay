<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function isAdmin() {
        $user = auth()->user();
        return $user->role == 'admin';
    }

    public function getUserList(Request $request)
    {
        // if (!$this->isAdmin()) {
        //     return response()->json([
        //         'message' => 'khong phai admin',
        //     ]);
        // }

        $searchContent = $request->searchContent;
        try {
           $users = User::where('name', 'like', "%$searchContent%")
                ->orWhere('phonenumber', 'like', "%$searchContent%")
                ->orWhere('email', 'like', "%$searchContent%")
                ->orWhere('address', 'like', "%$searchContent%")
                ->orWhere('status', 'like', "%$searchContent%")
                ->orWhere('role', 'like', "%$searchContent%")
                ->paginate(20);

            return response()->json([
                'success' => true,
                'users' => $users,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function changeAccountStatus($id, Request $request)
    {
        try {
            $user = User::find($id);
            $user->status = $request->status;
            $user->save();

            if ($request->status == 'banned')
                $message = 'Đã chặn người dùng';
            else if ($request->status == 'active')
                $message = 'Đã gỡ chặn người dùng';

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function showDetailOfAUser($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role == 'jobseeker') {
                $user = DB::table('users')
                    ->join('job_seekers', 'users.user_id', 'job_seekers.user_id')
                    ->where('users.user_id', '=', $id)
                    ->get();
                return $user;
            } else if ($user->role == 'employer') {
                $user = DB::table('users')
                    ->join('employers', 'users.user_id', 'employers.user_id')
                    ->where('users.user_id', '=', $id)
                    ->get();
                return $user;
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
