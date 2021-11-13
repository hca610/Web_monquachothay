@extends('master')
@section('title', 'List user')

@section('content')
    <div class="row">
        <div class="col">
            <h1>List user</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <ul>
                @foreach ($users as $user)
                    <li>
                        {{ $user->user_id }}
                        <a href="{{ route('user.show', ['user' => $user->user_id]) }}">{{ $user->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-4">
            <form action="{{ route('user.findUserByName') }}" method="post">
                @csrf
                <label for="name">Search user by name</label>
                <input class="form-control" type="text" name="name">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
    </div>
@endsection
