@extends('master')
@section('title', 'Create category')

@section('content')
    @if (session('success'))
        <div class="row">
            <div class="col">
                <div class="alert alert-success" role="alert">
                    Them bai viet thanh cong
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col">
            <h1>Create category</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name of category</label>
                    <input id="name" class="form-control" type="text" name="name">
                </div>
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </form>
        </div>
    </div>
@endsection
