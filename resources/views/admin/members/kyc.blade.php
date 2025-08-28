@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <h2>Manage Member KYC</h2>

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form method="POST" action="{{ route('manage.kyc') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="keyword" class="form-control" placeholder="Search by Name, Phone, Member ID" value="{{ old('keyword', $keyword) }}" />
                <button type="submit" class="btn btn-danger mt-2">Search Member</button>
            </div>
        </form>

        @if ($members->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SI.No.</th>
                        <th>Mem ID</th>
                        <th>Member Name</th>
                        <th>Mobile No</th>
                        <th>Pancard</th>
                        <th>Bank Name</th>
                        <th>Branch</th>
                        <th>Account No</th>
                        <th>IFSC</th>
                        <th>Pancard</th>
                        <th>Aadhar</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $index => $member)
                        <tr>
                            <td>{{ $members->firstItem() + $index }}</td>
                            <td>{{ $member->mem_id }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->mobileno }}</td>
                            <td>{{ $member->pannumber }}</td>
                            <td>{{ $member->bankDetails->bank_name ?? 'N/A' }}</td>
                            <td>{{ $member->bankDetails->branch ?? 'N/A' }}</td>
                            <td>{{ $member->bankDetails->acc_number ?? 'N/A' }}</td>
                            <td>{{ $member->bankDetails->ifsc_code ?? 'N/A' }}</td>
                            <td>
                                @if ($member->pancardpic)
                                    <a href="{{ asset('member/' . $member->pancardpic) }}" target="_blank">Click Here</a>
                                @else
                                    No Upload
                                @endif
                            </td>
                            <td>
                                @if ($member->aadharcardpic)
                                    <a href="{{ asset('member/' . $member->aadharcardpic) }}" target="_blank">Click Here</a>
                                @else
                                    No Upload
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('manage.kyc.update') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="RecID" value="{{ $member->mem_id }}" />
                                    <input type="hidden" name="approve" value="{{ $member->activate_date == 0 ? 1 : 0 }}" />
                                    <button type="submit" class="btn btn-{{ $member->activate_date == 0 ? 'success' : 'danger' }}">
                                        {{ $member->activate_date == 0 ? 'Approve' : 'Disapprove' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $members->links() }}
        @else
            <div class="alert alert-warning">No Data Available!</div>
        @endif
    </div>


       </div>
@endsection
