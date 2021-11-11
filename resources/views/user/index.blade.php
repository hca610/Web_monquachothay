@extends('master')
@section('title', 'List user')

@section('content')
    <div class="row">
        <div class="col">
            <h1>List user</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <ul>
                @foreach ($users as $user)
                    <li>{{ $user->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
