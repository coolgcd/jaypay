@extends('member.layout')

@section('content')
<style>
    .card-custom {
        border-radius: 10px;
        border: 1px solid #e3e3e3;
        background-color: #ffffff;
        margin-bottom: 1.5rem;
    }

    .card-header {
        font-weight: 600;
        background-color: #f1f5f9; /* Light bluish-gray */
        color: #333;
        border-bottom: 1px solid #dee2e6;
    }

    .wallet-summary p {
        margin-bottom: 0.5rem;
        font-size: 15px;
        color: #444;
    }

    .wallet-summary p strong {
        color: #007bff;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .table th {
        background-color: #f8fafc;
        font-weight: 600;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    @media (max-width: 576px) {
        .card-header, h4 {
            font-size: 1.1rem;
        }

        .table th, .table td {
            font-size: 13px;
            padding: 0.4rem;
        }

        .wallet-summary p {
            font-size: 14px;
        }
    }
</style>

<div class="container py-3">

    <h4 class="mb-3 text-primary">💸 Withdraw Request</h4>

    <!-- Wallet Summary -->
    <div class="card card-custom wallet-summary">
        <div class="card-header">Wallet Summary</div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <p><strong>Total Income:</strong><br> ₹{{ number_format($wallet['total_income'], 2) }}</p>
                </div>
                <div class="col-6 col-md-3">
                    <p><strong>Total Withdrawn:</strong><br> ₹{{ number_format($wallet['total_withdrawn'], 2) }}</p>
                </div>
                <div class="col-6 col-md-3">
                    <p><strong>Total Recharge:</strong><br> ₹{{ number_format($wallet['recharge'], 2) }}</p>
                </div>
                <div class="col-6 col-md-3">
                    <p><strong>Available Balance:</strong><br> ₹{{ number_format($wallet['balance'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Form -->
    <div class="card card-custom">
        <div class="card-header">Request Withdrawal</div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('member.withdraw.request') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="amount" class="form-label">Withdraw Amount (₹)</label>
                    <input type="number" name="amount" class="form-control" min="100" placeholder="Enter amount" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit Request</button>
            </form>
        </div>
    </div>

    <!-- Withdraw History -->
    <div class="card card-custom">
        <div class="card-header">Withdrawal History</div>
        <div class="card-body">
            @if($withdrawals->isEmpty())
                <div class="text-muted">No withdrawal requests yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Charge</th>
                                <th>Final</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Requested At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $w)
                            <tr>
                                <td>{{ $w->id }}</td>
                                <td>₹{{ number_format($w->amount, 2) }}</td>
                                <td>₹{{ number_format($w->charge, 2) }}</td>
                                <td>₹{{ number_format($w->final_amount, 2) }}</td>
                                <td>{{ ucfirst($w->method) }}</td>
                                <td>
                                    <span class="badge bg-{{ $w->status == 'approved' ? 'success' : ($w->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($w->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($w->requested_at)->format('d M Y, h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
