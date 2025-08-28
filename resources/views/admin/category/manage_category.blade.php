@extends('admin.layout')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="graphs">
            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <h3 class="blank1">Manage Categories</h3>
            <div class="xs">
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ route('categories.create') }}" class="btn btn-default">Add New Category</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Category Image</th>
                                <th>Category Title</th>
                                <th>Category Description</th>
                                <th>Add Date</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td align="center">
                                    @if($category->cat_image)
                                        <img src="{{ asset($category->cat_image) }}" height="60" width="100" style="border:2px solid #996600;" />
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $category->cat_title }}</td>
                                <td>{{ $category->cat_desc }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($category->add_date)->format('d-m-Y') }}</td>
                                {{-- <td>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td> --}}

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
