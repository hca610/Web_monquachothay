<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployerController extends Controller
{
    public function search(Request $request)
    {
        try {
            $employers = DB::table('employers')
                ->join('users', 'users.user_id', '=', 'employers.user_id')
                ->where('name', 'like', "%$request->name%")
                ->where('about_us', 'like', "%$request->about_us")
                ->where('username', 'like', "%$request->username%")
                ->where('phonenumber', 'like', "%$request->phonenumber%")
                ->where('email', 'like', "%$request->email%")
                ->where('address', 'like', "%$request->address%")
                ->where('status', 'like', "%$request->status%")
                ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Tìm thấy ' . $employers->total() . ' kết quả',
                'data' => $employers
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra!'
            ], 404);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->fill($request->all());
            $user->role = 'employer';
            $user_id = $user->save();

            $employer = new Employer();
            $employer->about_us = $request->about_us;
            $employer->image_link = $request->image_link;
            $employer->num_employee = $request->num_employee;
            $employer->category_id = $request->category_id;
            $employer->user_id = $user_id;

            return response()->json([
                'success' => true,
                'message' => 'Tạo tài khoản thành công!',
                'data' => $employer,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'sucess' => false,
                'message' => 'Tạo tài khoản thất bại!'
            ], 406);
        }
    }

    public function show($id)
    {
        try {
            $employer = Employer::findOrFail($id);
            // $employer = DB::table('employers')
            //     ->join('users', 'users.user_id', '=', 'employers.user_id')
            //     ->where('employer_id', '=', $id)
            //     ->get();
            // $user = User::findOrFail($employer->employer_id);
            $user = $employer->user();
            return response()->json([
                'success' => true,
                'data' => $employer,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
            ], 404);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
        $employer = Employer::find($id);
        $employer->fill($request->all());
        $employer->save();

        $user = User::find($employer->user_id);
        $user->fill($request->all());
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $employer,
        ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra'
            ]);
        }
    }

    public function destroy($id)
    {
        //
    }

    public function showRecruitments($employerId)
    {
        try {
            $employer = Employer::findOrFail($employerId);
            return $employer->recruitments();
        }
        catch (Exception $e) {

        }
    }
}
