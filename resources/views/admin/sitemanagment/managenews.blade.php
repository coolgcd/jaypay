@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <form name="manageslide" method="post" class="form">
            @csrf
            <div class="block" id="block-tables">
                <div class="secondary-navigation">
                    <div class="sec-name">Manage NEWS/Notice</div>
                    <div class="sec-button">
                        <div class="actions">
                            <a href="{{ route('news.create') }}" class="btn btn-info" style="margin-bottom: 10px;">Add New News</a>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="content">
                    <div class="inner">
                        @if(session('message'))
                            <div class="flash">
                                <div class="message notice">
                                    <p>{{ session('message') }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="first">SI.No.</th>
                                    <th>News</th>
                                    <th class="last">Manage Buttons</th>
                                </tr>
                                @forelse($news as $index => $newsItem)
                                    <tr class="{{ $index % 2 == 0 ? 'even' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $newsItem->news }}</td>
                                        <td nowrap="nowrap">
                                            <div class="actions-bar">
                                                <div class="actions">
                                                    <p>
                                                        <a href="{{ route('news.edit', $newsItem->id) }}" class="btn btn-danger">Edit</a>
                                                        <form action="{{ route('news.destroy', $newsItem->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?')">Delete</button>
                                                        </form>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            <div class="flash">
                                                <div class="message warning">
                                                    <p>No news added yet!</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
       </div>
@endsection
