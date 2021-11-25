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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'phonenumber' => 'required|string',
                'address' => 'required|string',
                'role' => 'required|string',
                'birthday' => 'required|date',
                'gender' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

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
                // return response()->json(['message' => 'Error']);
                return $e->getMessage();
            }
        } else if ($request->role == 'employer') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6',
                'phonenumber' => 'required|string',
                'address' => 'required|string',
                'role' => 'required|string',
                'about_us' => 'required|string',
                'num_employee' => 'required|integer',
                'category_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

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
                // return response()->json(['message' => 'Error']);
                return $e->getMessage();
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
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $userId = auth()->user()->user_id;

        $user = User::where('user_id', $userId)->update(
            ['password' => bcrypt($request->new_password)]
        );

        return response()->json([
            'message' => 'User successfully changed password',
            'user' => $user,
        ], 201);
    }

    public function show($id)
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

    public function search(Request $request)
    {
        $users = User::where('name', 'like', "%$request->all()->name%")
            ->where('username', 'like', "%$request->username%")
            ->where('phonenumber', 'like', "%$request->phonenumber%")
            ->where('email', 'like', "%$request->email%")
            ->where('address', 'like', "%$request->address%")
            ->where('status', 'like', "%$request->status%")
            ->where('role', 'like', "%$request->role%")
            ->paginate(20);

        return response()->json([
            'users' => $users,
        ]);
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
}
