@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container">
            <h2>Manage Members</h2>

            @if($msg)
                <div class="alert alert-success">{{ $msg }}</div>
            @endif

            <form method="POST" action="{{ url('/manage-members') }}">
                @csrf
                <div class="form-group">
                    <label for="keyword">Enter Name/ID:</label>
                    <input type="text" name="keyword" id="keyword" class="form-control" value="{{ old('keyword', $keywords) }}">
                    <input type="submit" class="btn btn-danger" value="Search Member">
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SI.No.</th>
                        <th>Memid</th>
                        <th>Member Name</th>
                        <th>Password</th>
                        <th>Parent ID</th>
                        <th>Sponsor ID</th>
                        <th>Direct</th>
                        <th>Activate Date</th>
                        <th>Join Date</th>
                        <th>Stockist</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $index => $member)
                        <tr>
                            <td>{{ $members->firstItem() + $index }}</td>
                            <td>{{ $member->mem_id }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->password }}</td>
                            <td>{{ $member->parentid }}</td>
                            <td>{{ $member->sponsorid }}</td>
                            <td>{{ $member->tot_ref }}</td>
                            <td>{{ date('h:s A d-m-Y', $member->activate_date) }}</td>
                            <td>{{ date('h:s A d-m-Y', $member->joindate) }}</td>
                            <td>{{ $member->cur_rank }}</td>
                            <td>
                                <a href="{{ url('/manage-members?opr=Deactivate&RecID=' . $member->mem_id) }}" class="btn btn-danger">Delete</a>
                                <!-- Add more action buttons as needed -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">No Data Available!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- {{ $members->links() }} --}}
        </div>
       </div>
@endsection
