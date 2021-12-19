<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\JobSeeker;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ]);
        } else {
            if (
                sizeof(User::where('email', $request->email)
                    ->where('status', 'banned')
                    ->get()) > 0
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản đã bị khóa',
                ]);
            }
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        try {
            if (sizeof(DB::table('users')->where('email', '=', $request->email)->get()) > 0) {
                throw new Exception('Email đã tồn tại');
            } else if (sizeof(DB::table('users')->where('phonenumber', '=', $request->phonenumber)->get()) > 0) {
                throw new Exception('Số điện thoại đã được sử dụng');
            }

            $user = new User();
            $user->fill($request->all());
            $user->password = bcrypt($request->password);
            $user->save();

            switch ($request->role) {
                case 'jobseeker':
                    $jobseeker = new JobSeeker();
                    $jobseeker->fill($request->all());
                    $jobseeker->user_id = $user->user_id;
                    $jobseeker->save();
                    break;
                case 'employer':
                    $employer = new Employer();
                    $employer->fill($request->all());
                    $employer->user_id = $user->user_id;
                    $employer->save();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Đăng kí thành công',
                'user' => $user,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
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

    public function changePassWord(Request $request)
    {
        try {
            $userId = auth()->user()->user_id;

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

    static function simplecheckrole($role)
    {
        if (auth()->user()->role == $role)
            return true;
        else
            return false;
    }

    static function checkrole($role)
    {
        if (!self::simplecheckrole($role)) {
            throw new Exception('Ban khong phai la ' . $role);
        }
        return $role;
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

    public function searchUser(Request $request)
    {
        try {
            $users = User::where('name', 'like', '%' . $request->name . '%')
                ->where('email', 'like', '%' . $request->email . '%')
                ->get();

            return response()->json([
                'success' => true,
                'users' => $users,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra',
            ]);
        }
    }
}
