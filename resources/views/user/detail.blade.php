@extends('master')
@section('title', $user->name)

@section('content')

    <div class="row">
        <div class="col">
            <br>
            <form action="{{ route('user.update', ['user' => $user->user_id]) }}" method="post">
                @csrf
                <label for="user_id">User id</label>
                <input type="text" class="form-control" name="user_id" value="{{ $user->user_id }}">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" value="{{ $user->username }}">
                <label for="phonenumber">Phone number</label>
                <input type="text" class="form-control" name="phonenumber" value="{{ $user->phonenumber }}">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" value="{{ $user->email }}">
                <label for="address">Address</label>
                <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                <button class="btn btn-primary" type="submit">Edit</button>
            </form>
        </div>
    </div>

@endsection
