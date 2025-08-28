@extends('member.layout')

@section('title', 'My Recharge History')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">My Recharge Requests</h2>

    @if($recharges->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Mobile</th>
                    <th>Operator</th>
                    <th>Amount (₹)</th>
                    <th>Status</th>
                    <th>Message</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recharges as $r)
                <tr>
                    <!-- <td>{{ $r->id }}</td> -->
                    <td>{{ $r->number }}</td>
                    <td>{{ $r->operator }}</td>
                    <td>{{ number_format($r->amount, 2) }}</td>
                    <td>
                        @php
                            $statusClass = match(strtolower($r->status)) {
                                'success' => 'badge bg-success',
                                'accepted' => 'badge bg-primary',
                                'pending' => 'badge bg-warning text-dark',
                                'failed', 'error' => 'badge bg-danger',
                                default => 'badge bg-secondary'
                            };
                        @endphp
                        <span class="{{ $statusClass }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                    <td>{{ $r->message ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-info">
        No recharge requests found.
    </div>
    @endif
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endsection
