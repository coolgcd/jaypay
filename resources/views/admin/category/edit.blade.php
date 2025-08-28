@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            <h3 class="blank1">Edit Category</h3>
            <form action="{{ route('categories.update', $category->category_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="cat_title">Category Title</label>
                    <input type="text" name="cat_title" class="form-control" id="cat_title" value="{{ $category->cat_title }}" required>
                </div>
                <div class="form-group">
                    <label for="cat_desc">Category Description</label>
                    <textarea name="cat_desc" class="form-control" id="cat_desc">{{ $category->cat_desc }}</textarea>
                </div>
                <div class="form-group">
                    <label for="cat_image">Category Image</label>
                    <input type="file" name="cat_image" class="form-control" id="cat_image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </form>
        </div>
       </div>
@endsection
