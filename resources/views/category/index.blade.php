@extends('master')
@section('title', 'Category')

@section('content')
    <div class="row">
        <div class="col">
            <h1> List of category</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <ul>
                @foreach ($categories as $category)
                    <li>{{ $category->name }}</li>
                @endforeach

            </ul>
        </div>
    </div>
@endsection
