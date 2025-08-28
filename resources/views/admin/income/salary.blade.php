@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Salary Income</h4>

    <!-- Filters -->
    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <input type="text" name="member_id" value="{{ request('member_id') }}" class="form-control" placeholder="Member ID">
        </div>
        <div class="col-md-3">
            <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Member Name">
        </div>
        <div class="col-md-3">
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
        </div>
        <div class="col-md-2 mt-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Matching Income</th>
                    <th>From Date</th>
                    <th>To Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salaryIncomes as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->member_id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>₹{{ number_format($row->amount, 2) }}</td>
                    <td>{{ $row->matching_income }}</td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($row->from_date)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($row->to_date)->format('d-m-Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $salaryIncomes->appends(request()->all())->links() }}
    </div>
</div>
@endsection
