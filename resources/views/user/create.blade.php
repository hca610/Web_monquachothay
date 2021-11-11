@extends('master')
@section('title', 'Create user')

@section('content')
    <div class="row">
        <div class="col">
            <form method="post" action="{{ route('user.create') }}">
                <label for="name">Name</label>

            </form>
        </div>
    </div>
@endsection
