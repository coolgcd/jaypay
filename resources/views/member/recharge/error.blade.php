@extends('member.layout') {{-- or whatever your layout file is --}}

@section('title', 'Recharge Failed')

@section('content')
<div class="container py-5">
    <div class="card shadow rounded-lg border-danger">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Recharge Failed</h4>
        </div>
        <div class="card-body">
            <p class="text-danger mb-3">
                <strong>Status:</strong> {{ $data['status'] ?? 'Error' }}<br>
                <strong>Message:</strong> {{ $data['message'] ?? 'Unknown error occurred.' }}
            </p>

            <hr>

            <h5>Recharge Details</h5>
            <ul class="list-group">
                <li class="list-group-item"><strong>Mobile Number:</strong> {{ $data['number'] ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Amount:</strong> ₹{{ $data['amount'] ?? '0.00' }}</li>
                <li class="list-group-item"><strong>Operator:</strong> {{ $data['operator'] ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Ref ID:</strong> {{ $data['ref_id'] ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Txn ID:</strong> {{ $data['txn_id'] ?? '-' }}</li>
                <li class="list-group-item"><strong>Operator ID:</strong> {{ $data['opt_id'] ?? '-' }}</li>
                <li class="list-group-item"><strong>Balance After:</strong> ₹{{ $data['balance'] ?? '-' }}</li>
            </ul>

            <div class="mt-4 text-center">
                <a href="{{ route('member.recharge.mobile') }}" class="btn btn-primary">Try Again</a>
            </div>
        </div>
    </div>
</div>
@endsection
