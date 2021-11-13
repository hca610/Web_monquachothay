<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        // return view('/user/index')->with('users', $users);
        return $users;
    }

    public function create()
    {
        return view('/user/create');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user.detail')->with('user', $user);
    }

    public function store(Request $request)
    {
        $user = new User();
        echo $request;
        $user->fill($request->all());
        $user->save();

        return redirect('/user/create')->with('success', true);
    }

    public function findUserByName(Request $request)
    {
        $users = User::where('name', 'like', "%$request->name%")->get();
        foreach ($users as $user) {
            echo  $user->name.'<br>';
        }
        // return $users;
    }

    public function edit(Request $request)
    {
        // $user = User::findOrFail($request->user_id);
        // return view('user.edit')->with('user', $user);
    }

    public function update(Request $request)
    {
        $user = User::find($request->user_id);
        $user->fill($request->all());
        $user->save();
        return $user;
    }

    public function destroy($id)
    {
        //
    }

    public function banUser(Request $request)
    {
        $user = User::find($request->user_id);
        $user->status = 'banned';
        $user->save();
    }
}
