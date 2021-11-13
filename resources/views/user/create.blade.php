@extends('master')
@section('title', 'Create user')

@section('content')
    <div class="row">
        <div class="col">

            @if (session('success'))
                <div class="alert alert-success">Create user success</div>
            @endif

            <br>
            <form action="{{ route('user.store') }}" method="post">
                @csrf
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username">

                <label for="password">Password</label>
                <input type="text" class="form-control" name="password">

                <label for="phonenumber">Phone number</label>
                <input type="text" class="form-control" name="phonenumber">

                <label for="email">Email</label>
                <input type="text" class="form-control" name="email">

                <label for="address">Address</label>
                <input type="text" class="form-control" name="address">
                <label for="status">Status</label>
                <input type="text" class="form-control" name="status">
                <button class="btn btn-primary">Create</button>
            </form>
        </div>

    </div>
@endsection
