@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            @if(session('message'))
                <div class="myalert-unsus">
                    {{ session('message') }}
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
            @endif
            <h3 class="blank1">Manage Category</h3>
            <div class="xs">
                <div style="overflow:hidden">
                    <a href="{{ route('categories.create') }}" class="btn btn-default" style="float:right; margin-bottom:10px">Add New Category</a>
                </div>
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered table-striped">
                        <tr style="background:#FFD3A8!important;">
                            <td width="5%" nowrap><strong>Sr. No.</strong></td>
                            <td width="12%" nowrap><strong>Category Image</strong></td>
                            <td width="61%" nowrap><strong>Category Title</strong></td>
                            <td width="22%" align="center" nowrap><strong>Actions</strong></td>
                        </tr>
                        @if($categories->count())
                            @foreach($categories as $index => $category)
                                <tr>
                                    <td nowrap>{{ $index + 1 }}</td>
                                    <td align="center" nowrap>
                                        @if(file_exists(public_path($category->cat_image)))
                                            <img src="{{ asset($category->cat_image) }}" height="60" width="100" style="border:2px solid #996600;" />
                                        @endif
                                    </td>
                                    <td nowrap>{{ $category->cat_title }}</td>
                                    <td align="center" nowrap>
                                        <form action="{{ route('categories.destroy', $category->category_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                        <a href="{{ route('categories.edit', $category->category_id) }}" class="btn btn-warning">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" nowrap style="color:#FF0000; text-align:center;"> Category not added yet!</td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
       </div>
@endsection
