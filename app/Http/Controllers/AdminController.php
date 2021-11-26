<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function getUserList(Request $request)
    {
        try {
            $users = User::where('name', 'like', "%$request->name%")
                ->where('phonenumber', 'like', "%$request->phonenumber%")
                ->where('email', 'like', "%$request->email%")
                ->where('address', 'like', "%$request->address%")
                ->where('status', 'like', "%$request->status%")
                ->where('role', 'like', "%$request->role%")
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

    public function banUser($id)
    {
        try {
            $user = User::find($id);
            $user->status = 'banned';
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã chặn người dùng',
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
