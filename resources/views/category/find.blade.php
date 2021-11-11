@extends('master')
@section('title', 'Find user')

@section('content')
    <div class="row">
        <div class="col">
            <form action="{{ route('category.findCategoryByName') }}" method="get">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
    <br>

    @if (session('success'))
        @foreach ($categories as $category)
            '<br>'.{{  $catergory->name }};
        @endforeach
    @else
        Nothing
    @endif
@endsection
