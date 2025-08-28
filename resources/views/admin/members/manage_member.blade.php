@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">

    <form method="POST" class="form">
        @csrf
        <div class="block" id="block-tables">
            <div class="secondary-navigation">
                <div class="sec-name">Manage Member</div>
                <div class="sec-button">
                    <div class="actions">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="font-weight:bold;">Enter Name/ID:&nbsp;</td>
                                    <td><input name="keyword" type="text" class="form-control" id="keyword" value="{{ old('keyword', $keywords) }}" /></td>
                                    <td><input name="submit" type="submit" class="btn btn-danger" value="Search Member" /></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="content">
                <div class="inner">
                    @if($msg)
                        <div class="flash">
                            <div class="message notice">
                                <p>{{ $msg }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>SI.No.</th>
                                <th>Memid</th>
                                <th>Member Name</th>
                                <th>Password</th>
                                <th>Parent ID</th>
                                <th>ICARD</th>
                                <th>Package Join</th>
                                <th>Join Date</th>
                                <th>Actions</th>
                            </tr>

                            @foreach ($members as $index => $member)
                            <tr>
                                <td>{{ $members->firstItem() + $index }}</td>
                                <td>{{ $member->mem_id }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->password }}</td>
                                <td>{{ $member->parentid }}</td>
                                <td><a href="{{ url('icard', ['MEMID' => $member->mem_id]) }}" class="button" target="_blank">CR</a></td>
                                <td>{{ $member->packagejoin }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($member->joindate)->format('d-m-Y') }}</td>
                                <td>
                                    <form action="{{ route('member.post') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="RecID" value="{{ $member->mem_id }}">

                                        <button type="submit" name="opr" value="status" class="btn btn-success" {{ $member->active == 'yes' ? 'disabled' : '' }}>
                                            Activate
                                        </button>

                                        <button type="submit" name="opr" value="status" class="btn btn-danger" {{ $member->inactive == 'yes' ? 'disabled' : '' }}>
                                            Deactivate
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        </table>
                    </div>

                    {{-- {{ $members->links() }} <!-- Pagination links --> --}}
                </div>
            </div>
        </div>
    </form>
       </div>
@endsection
