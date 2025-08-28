@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Reward Income</h4>

    <!-- Search Filter -->
    <form method="GET" action="" class="mb-3 row g-2">
        <div class="col-md-3">
            <input type="text" name="member_id" class="form-control" placeholder="Search by Member ID" value="{{ request('member_id') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Search by Name" value="{{ request('name') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Matching Pair</th>
                    <th>Rank</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rewards as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->member_id }}</td>
                        <td>{{ $row->member_name }}</td>
                        <td>{{ $row->matching_pair }}</td>
                        <td>{{ $row->rank }}</td>
                        <td>₹{{ number_format($row->amount, 2) }}</td>
                        <td>
                            @if($row->status)
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-warning text-dark">Unpaid</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No reward income records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $rewards->withQueryString()->links() }}
    </div>
</div>
@endsection
