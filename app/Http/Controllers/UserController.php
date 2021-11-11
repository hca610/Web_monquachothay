<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('/user/index')->with('users', $users);
    }

    public function create()
    {
        return view('/user/create');
    }

    public function show()
    {
    }

    public function createUser(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->save();
    }

    public function search()
    {
        echo "hiihih";
        return view('user.find');
    }

    public function findUserByName($name)
    {
        $users = User::where('name', 'like', "%$name%")->get();
        foreach ($users as $user) {
            echo '<br>' . $user->name;
        }
        return $users;
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
    }

    public function changeStatus($id, $status)
    {
        $user = User::findOrFail($id);
        $user->status = $status;
        $user->save();
    }
}
