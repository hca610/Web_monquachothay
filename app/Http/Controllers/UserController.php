<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        return $users;
    }

    public function create()
    {
        // return view('/user/create');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->save();

        return $user;
    }

    public function edit(Request $request)
    {
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
        $user = User::query()
            ->where('name', 'like', "%$request->name%")
            ->where('username', 'like', "%$request->username%")
            ->where('phonenumber', 'like', "%$request->phonenumber%")
            ->where('email', 'like', "%$request->email%")
            ->where('address', 'like', "%$request->address%")
            ->where('status', 'like', "%$request->status%")
            ->where('role', 'like', "%$request->role%")
            ->get();
        return $user;
    }

    public function banUser($id)
    {
        $user = User::find($id);
        $user->status = 'banned';
        $user->save();
    }
}
