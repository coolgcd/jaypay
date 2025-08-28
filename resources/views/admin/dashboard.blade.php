@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Manage Members</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                <li class="breadcrumb-item active">Manage Members</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Members List</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            {{-- <form method="POST" class="form">
                                @csrf
                                <div class="mb-3">
                                    <label for="keyword" class="form-label">Enter Name/ID:</label>
                                    <input name="keyword" type="text" class="form-control" id="keyword" value="{{ old('keyword', $keywords) }}" />
                                </div>
                                <button type="submit" class="btn btn-danger">Search Member</button>
                            </form>

                            @if($msg)
                                <div class="flash mt-3">
                                    <div class="message notice">
                                        <p>{{ $msg }}</p>
                                    </div>
                                </div>
                            @endif --}}

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead>
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
                                    </thead>
                                    {{-- <tbody>
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
                                                    <form action="{{ route('members.update', $member->mem_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" name="opr" value="status" class="btn btn-success" {{ $member->active == 'yes' ? 'disabled' : '' }}>Activate</button>
                                                        <button type="submit" name="opr" value="status" class="btn btn-danger" {{ $member->inactive == 'yes' ? 'disabled' : '' }}>Deactivate</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table> --}}
                            </div>

                            {{-- {{ $members->links() }} <!-- Pagination links --> --}}
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © Velzon.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
