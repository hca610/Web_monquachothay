<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getAllUser()
    {
        $users = User::all();
        return $users;
    }

    public function create()
    {
        //
    }

    public function createUser(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->save();
    }

    public function findUserByName($name)
    {
        $users = User::where('name', 'like', "%$name%")->get();
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
