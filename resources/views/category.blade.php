@extends('master')
@section('title', 'Category')

@section('content')
    @foreach ($categories as $category)
        <h1>{{ $category->name }}</h1>
    @endforeach
@endsection
