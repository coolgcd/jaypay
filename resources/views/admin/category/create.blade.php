@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            <h3 class="blank1">Add New Category</h3>
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="cat_title">Category Title</label>
                    <input type="text" class="form-control" id="cat_title" name="cat_title" required>
                </div>
                <div class="form-group">
                    <label for="cat_desc">Category Description</label>
                    <textarea class="form-control" id="cat_desc" name="cat_desc"></textarea>
                </div>
                <div class="form-group">
                    <label for="cat_image">Category Image</label>
                    <input type="file" class="form-control" id="cat_image" name="cat_image">
                </div>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
        </div>
       </div>
@endsection
