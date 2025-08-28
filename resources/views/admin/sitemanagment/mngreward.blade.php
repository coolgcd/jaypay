@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="block" id="block-tables">
            <div class="secondary-navigation">
                <div class="sec-name">Manage Reward</div>
                <div class="sec-button">
                    <div class="actions"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="content">
                <div class="inner">
                    <div align="right" style="margin-bottom: 15px;">
                        <a href="" class="btn btn-info">Add Reward</a>
                    </div>

                    @if(session('message'))
                        <div class="flash">
                            <div class="message notice">
                                <p>{{ session('message') }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('rewards.index') }}">
                        <input type="text" name="keyword" value="{{ $keywords ?? '' }}" placeholder="Search by Name" />
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th align="center" nowrap="nowrap" class="first">SI.No.</th>
                                <th align="left" nowrap="nowrap">MemID</th>
                                <th align="left" nowrap="nowrap">Name</th>
                                <th align="left" nowrap="nowrap">Used My Pool</th>
                                <th align="left" nowrap="nowrap">Reward</th>
                                <th align="left" nowrap="nowrap">Achieve Date</th>
                                <th nowrap="nowrap" class="last">Delete</th>
                            </tr>

                            {{-- @if($rewards->count()) --}}
                                @foreach($rewards as $index => $reward)
                                    <tr class="{{ $index % 2 == 0 ? 'even' : '' }}">
                                        <td align="center">{{ $rewards->firstItem() + $index }}</td>
                                        <td nowrap="nowrap">{{ $reward->mem_id }}</td>
                                        <td>{{ $reward->name }}</td>
                                        <td>{{ $reward->team }}</td>
                                        <td>{{ $reward->award }}</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($reward->DATE)->format('d-m-Y') }}</td>
                                        <td nowrap="nowrap">
                                            <form action="{{ route('rewards.destroy', $reward->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            {{-- @else --}}
                                <tr>
                                    <td colspan="7">
                                        <div class="flash">
                                            <div class="message warning">
                                                <p align="center" style="color:#FF0000;">No Data Available!</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            {{-- @endif --}}
                        </table>
                    </div>

                    {{-- {{ $rewards->links() }} <!-- Pagination links --> --}}
                </div>
            </div>
        </div>
       </div>
@endsection
