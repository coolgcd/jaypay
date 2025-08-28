@extends('member.layout')

@section('content')
<style>
    .card-custom {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        font-weight: 600;
        background-color: #f1f1f1;
        padding: 12px 20px;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.6em;
    }

    @media (max-width: 576px) {
        .card-header, h4 {
            font-size: 1.1rem;
        }

        .table th, .table td {
            font-size: 13px;
            padding: 0.4rem;
        }

        h4 {
            text-align: center;
        }
    }
</style>

<div class="container py-3">
    <h4 class="mb-3">📄 Payment History</h4>

    <div class="card card-custom">
        <div class="card-header">Transactions</div>
        <div class="card-body">
            @if($payments->isEmpty())
                <div class="text-muted text-center py-3">No payments found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Direction</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                       <tbody>
@foreach($payments as $payment)
    <tr>
        <td>{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>
        <td>{{ ucfirst($payment->type) }}</td>
        <td>₹{{ number_format($payment->amount, 2) }}</td>
        <td>
            @php
                $isIncome = $payment->type === 'income';
                $isReceived = !$isIncome && $payment->direction === 'debit';
                $isPaid = !$isIncome && $payment->direction === 'credit';
            @endphp

            @if($isIncome)
                <span class="badge bg-info">Slab Amount</span>
            @elseif($isReceived)
                <span class="badge bg-success">Received</span>
            @elseif($isPaid)
                <span class="badge bg-secondary">Paid</span>
            @endif
        </td>

        <td>
            @if($isIncome)
                Slab: ₹{{ number_format($payment->slab_amount ?? 0, 2) }}
            @else
                {{ $payment->description ?? '-' }}
            @endif
        </td>

        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, h:i A') }}</td>
    </tr>
@endforeach
</tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
