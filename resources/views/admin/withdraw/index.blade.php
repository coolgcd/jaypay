@extends('admin.layout')

@section('content')
<div class="container">
    <h4 class="mb-4">All Withdrawal Requests</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Amount</th>
<th>Charge (10%)</th>
<th>Final Amount</th>

                    <th>Method</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
              @forelse($requests as $withdrawal)
    <tr>
        <td>{{ $withdrawal->id }}</td>
        <td>{{ $withdrawal->member_id }}</td>
        <td>{{ $withdrawal->member_name ?? 'N/A' }}</td>

        <td>₹{{ number_format($withdrawal->amount, 2) }}</td>
<td>₹{{ number_format($withdrawal->charge ?? ($withdrawal->amount * 0.10), 2) }}</td>
<td>₹{{ number_format($withdrawal->final_amount ?? ($withdrawal->amount * 0.90), 2) }}</td>

        <td>{{ ucfirst($withdrawal->method) }}</td>
        <td>
            <span class="badge bg-{{ $withdrawal->status == 'approved' ? 'success' : ($withdrawal->status == 'rejected' ? 'danger' : 'warning') }}">
                {{ ucfirst($withdrawal->status) }}
            </span>
        </td>
        <td>{{ \Carbon\Carbon::parse($withdrawal->requested_at)->format('d M Y, h:i A') }}</td>
        <td>
            @if($withdrawal->status === 'pending')
                <form method="POST" action="{{ route('admin.withdraw.update', $withdrawal->id) }}" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                    <button name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                </form>
            @else
                <em>No actions</em>
            @endif
        </td>
    </tr>
@empty
    <tr><td colspan="8">No withdrawal requests found.</td></tr>
@endforelse

            </tbody>
        </table>
    </div>
</div>
@endsection
