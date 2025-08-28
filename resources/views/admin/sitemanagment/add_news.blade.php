@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <form action="{{ route('news.store') }}" method="POST" class="form">
            @csrf
            <div class="form-group">
                <label for="news">News:</label>
                <textarea name="news" id="news" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add News</button>
        </form>
       </div>
@endsection
