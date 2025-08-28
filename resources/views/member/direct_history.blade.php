@extends('member.layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background: #ecf0f1;
        font-family: 'Poppins', sans-serif;
    }

    .glass-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #dce3e8;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
        transition: transform 0.3s ease;
        padding: 1.5rem;
    }

    .card-header-custom {
        background: linear-gradient(to right, #2c3e50, #34495e);
        color: white;
        padding: 1.25rem 1.5rem;
        font-size: 1.3rem;
        font-weight: 600;
        border-radius: 16px 16px 0 0;
    }

    .highlight-amount {
        color: #27ae60;
        font-weight: bold;
    }

    .table th {
        background: #2c3e50;
        color: #fff;
        text-align: center;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .member-info {
        background: #f4f6f7;
        padding: 1rem;
        margin-top: 1.5rem;
        border-radius: 12px;
        font-size: 1rem;
    }

    .member-info i {
        color: #2980b9;
        margin-right: 6px;
    }
</style>

<div class="glass-card animate__animated animate__fadeIn my-4">
    <div class="card-header-custom">
        <i class="bi bi-clock-history"></i> Direct Income History
    </div>
 <div class="member-info d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <div><i class="bi bi-person-circle"></i> <strong>Member ID:</strong> {{ $referredMember->show_mem_id ?? $memberId }}</div>
        <div><i class="bi bi-person-fill"></i> <strong>Name:</strong> {{ $referredMember->name ?? 'N/A' }}</div>
    </div>
    <div class="text-end mt-3 mt-md-0">
        <div><i class="bi bi-wallet2"></i> <strong>Total Received:</strong></div>
        <div class="highlight-amount fs-5">₹{{ number_format($totalReceived, 2) }}</div>
    </div>
</div>


    @if($incomeHistory->count())
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incomeHistory as $index => $record)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->created_at)->format('l') }}</td>
                        <td class="highlight-amount">₹{{ number_format($record->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center mt-3">No history found for this sponsor.</div>
    @endif
</div>
@endsection
