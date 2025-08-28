@extends('admin.layout')

@section('content')
<div class="container">
    <h4 class="mb-4">Recharge History</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Mobile Number</th>
                    <th>Operator</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recharges as $recharge)
                    <tr>
                        <td>{{ $recharge->id }}</td>
                        <td>{{ $recharge->member_id }}</td>
<td>{{ $recharge->member_name ?? 'N/A' }}</td>
                        <td>{{ $recharge->number }}</td>
                        <td>{{ strtoupper($recharge->operator) }}</td>
                        <td>₹{{ number_format($recharge->amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $recharge->status === 'success' ? 'success' : ($recharge->status === 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($recharge->status) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($recharge->created_at)->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No recharge history found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
