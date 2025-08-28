@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <form action="{{ route('news.update', $news->id) }}" method="POST" class="form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="news">News:</label>
                <textarea name="news" id="news" class="form-control" required>{{ $news->news }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update News</button>
        </form>
       </div>
@endsection
