<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\JobSeeker;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Tên đăng nhập hoặc mật khẩu không đúng'], 401);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        if ($request->role == 'jobseeker') {
            try {
                $user = new User();
                $user->fill($request->all());
                $user->password = bcrypt($request->password);
                $user->save();

                $jobseeker = new JobSeeker();
                $jobseeker->fill($request->all());
                $jobseeker->user_id = $user->user_id;
                $jobseeker->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Đăng kí thành công',
                    'user' => $user,
                    'jobseeker' => $jobseeker,
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã có lỗi xảy ra',
                ]);
            }
        } else if ($request->role == 'employer') {
            try {
                $user = new User();
                $user->fill($request->all());
                $user->password = bcrypt($request->password);
                $user->save();

                $employer = new Employer();
                $employer->fill($request->all());
                $employer->user_id = $user->user_id;
                $employer->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Đăng kí thành công',
                    'user' => $user,
                    'employer' => $employer,
                ], 201);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã có lỗi xảy ra',
                ]);
            }
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        try {
            $user = auth()->user();

            if ($user->role == 'jobseeker') {
                return response()->json([
                    'user' => $user,
                    'jobseeker' => $user->jobSeeker,
                ]);
            } else if ($user->role == 'employer') {
                return response()->json([
                    'user' => $user,
                    'employer' => $user->employer,
                ]);
            }
            return $user;
        } catch (Exception $e) {
            return response()->json([
                'success' => 'false',
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function changePassWord(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|min:6',
                'new_password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                throw new Exception();
            }
            $userId = auth()->user()->user_id;

            if (bcrypt($request->old_password) != auth()->user()->password) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu cũ không đúng',
                ]);
            }
            $user = User::where('user_id', $userId)->update(
                ['password' => bcrypt($request->new_password)]
            );

            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đổi mật khẩu không thành công',
            ]);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();
            $user->fill($request->all());

            if ($user->role == 'jobseeker') {
                $jobSeeker = $user->jobSeeker;
                $jobSeeker->fill($request->all());
                $user->save();
                $jobSeeker->save();
            } else if ($user->role == 'employer') {
                $user->employer->fill($request->all());
                $user->save();
                $user->employer->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ thành công',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
