@extends('admin.layout')

@section('content')
<div class="container">
    <h4 class="mb-3">Direct Income</h4>

    <!-- Filter Form -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <input type="text" name="show_mem_id" value="{{ request('show_mem_id') }}" class="form-control" placeholder="Member ID">
        </div>
        <div class="col-md-3">
            <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Name">
        </div>
        <div class="col-md-3">
            <input type="date" name="date" value="{{ request('date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    @if($records->count())
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>From ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->member_id }}</td>
                    <td>{{ $row->name ?? 'N/A' }}</td>
                    <td>{{ $row->from_id }}</td>
                    <td>₹{{ number_format($row->amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $records->withQueryString()->links() }}
    </div>
    @else
    <div class="alert alert-info">No direct income records found.</div>
    @endif
</div>
@endsection
