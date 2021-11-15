<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployerController extends Controller
{
    public function search(Request $request)
    {
        $jobSeekers = DB::table('employers')
            ->join('users', 'users.user_id', '=', 'employers.user_id')
            ->where('name', 'like', "%$request->name%")
            ->where('about_us', 'like', "%$request->about_us")
            ->where('username', 'like', "%$request->username%")
            ->where('phonenumber', 'like', "%$request->phonenumber%")
            ->where('email', 'like', "%$request->email%")
            ->where('address', 'like', "%$request->address%")
            ->where('status', 'like', "%$request->status%")
            ->paginate(20);

        return $jobSeekers;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
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

        $employer->save();
    }

    public function show($id)
    {
        $employer = Employer::find($id);
        if ($employer!= NULL) {
            return DB::table('employers')
                ->join('users', 'users.user_id', '=', 'employers.user_id')
                ->where('employer_id', '=', $id)
                ->get();
        } else {
            return 'Not found';
        }
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $employer = Employer::find($id);
        $employer->fill($request->all());
        $employer->save();

        $user = User::find($employer->user_id);
        $user->fill($request->all());
        $user->save();
    }

    public function destroy($id)
    {
        //
    }

    public function showRecruitment($employerId, $name)
    {
    }
}
