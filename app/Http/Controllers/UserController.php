<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function create()
    {
        // return view('/user/create');
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $user->jobSeeker;

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
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }

    public function update(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $user->fill($request->all());
        $user->save();

        return $user;
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

        // return $users;
        return $request->all();
    }

    public function banUser($id)
    {
        $user = User::find($id);
        $user->status = 'banned';
        $user->save();
    }
}
