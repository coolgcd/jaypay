@extends('member.layout')

@section('content')
<!-- CSS: Bootstrap + Animate + Custom -->
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
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
    }

    .card-header-custom {
        background: linear-gradient(to right, #2c3e50, #34495e);
        color: white;
        padding: 1.25rem 1.5rem;
        font-size: 1.3rem;
        font-weight: 600;
        border-bottom: none;
    }

    .summary {
        font-size: 1rem;
        margin-bottom: 1rem;
        color: #2c3e50;
    }

    .summary i {
        color: #2980b9;
        margin-right: 6px;
    }

    .fancy-table thead th {
        background: #2c3e50;
        color: #fff;
        text-align: center;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .fancy-table tbody tr.running {
        background-color: rgba(46, 204, 113, 0.05);
    }

    .fancy-table tbody tr.completed {
        background-color: rgba(241, 196, 15, 0.06);
    }

    .fancy-table tbody tr:hover {
        background-color: rgba(44, 62, 80, 0.07);
    }

    .badge-live {
        background: #2ecc71;
        color: white;
        font-size: 0.8rem;
        padding: 0.4rem 0.7rem;
        border-radius: 50rem;
    }

    .badge-finished {
        background: #e67e22;
        color: white;
        font-size: 0.8rem;
        padding: 0.4rem 0.7rem;
        border-radius: 50rem;
    }

    .highlight-cash {
        font-weight: 600;
        color: #27ae60;
    }

    .btn-history {
        background: #2980b9;
        color: white;
        border: none;
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 50rem;
        transition: 0.3s;
    }

    .btn-history:hover {
        background: #3498db;
        box-shadow: 0 0 10px rgba(41, 128, 185, 0.4);
    }

    .table td,
    .table th {
        vertical-align: middle;
        text-align: center;
    }
</style>


<!-- <div class="container py-5"> -->
    <div class="glass-card animate__animated animate__fadeIn">
        <div class="card-header-custom">
            <i class="bi bi-cash-stack"></i> My Direct Income
        </div>
        <div class="card-body">
            <div class="row text-center summary">
                <div class="col-md-6">
                    <i class="bi bi-person-badge-fill"></i> <strong>Sponsor ID:</strong> {{ $memid }}
                </div>
                <div class="col-md-6">
                    <i class="bi bi-wallet-fill"></i> <strong>Total Received:</strong>
                    <span class="highlight-cash">₹{{ number_format($totalReceived, 2) }}</span>
                </div>
            </div>

            @if($directPayments->count())
                <div class="table-responsive mt-4">
                    <table class="table table-hover fancy-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="bi bi-person-circle"></i> Member</th>
                                <th><i class="bi bi-box"></i> Package</th>
                                <th><i class="bi bi-currency-rupee"></i> Total Received</th>
                                <th><i class="bi bi-clock-history"></i> Per Day</th>
                                <th><i class="bi bi-calendar-plus"></i> Started</th>
                                <th><i class="bi bi-calendar-check"></i> Ends</th>
                                <th>Status</th>
                                <th><i class="bi bi-eye"></i> History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($directPayments as $payment)
                            <tr class="{{ $payment->total_received >= $payment->amount ? 'completed' : 'running' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->from_id }}</td>
                                <td>₹{{ number_format($payment->amount, 2) }}</td>
                                <td class="highlight-cash">₹{{ number_format($payment->total_received, 2) }}</td>
                                <td>₹{{ number_format($payment->amount * 0.005, 2) }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($payment->start_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($payment->end_date)->format('d M Y') }}</td>
                                <td>
                                    @if($payment->total_received >= $payment->amount)
                                        <span class="badge-finished">Completed</span>
                                    @else
                                        <span class="badge-live">Running</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('member.history.view', $payment->from_id) }}" class="btn btn-history">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $directPayments->links() }}
                </div>
            @else
                <div class="alert alert-info text-center mt-3">No direct payments found.</div>
            @endif
        </div>
    </div>

@endsection
