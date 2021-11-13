@extends('master')
@section('title', 'Create jobseeker')

@section('content')
    <div class="row">
        <div class="col">

            @if (session('success'))
                <div class="alert alert-success">Create user success</div>
            @endif

            <br>
            <form action="{{ route('jobseeker.store') }}" method="post">
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

                <label for="birthday">Birthday</label>
                <input type="text" class="form-control" name="birthday">

                <label for="gender">Gender</label>
                <input type="text" class="form-control" name="gender">

                <label for="qualification">Qualification</label>
                <input type="text" class="form-control" name="qualification">

                <label for="work_experience">Work experience</label>
                <input type="text" class="form-control" name="work_experience">

                <label for="education">Education</label>
                <input type="text" class="form-control" name="education">

                <label for="skill">Skill</label>
                <input type="text" class="form-control" name="skill">

                <button class="btn btn-primary">Create</button>
            </form>
        </div>

    </div>
@endsection
