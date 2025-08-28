@extends('member.layout')

@section('content')
<!-- CSS Dependencies -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #2c3e50, #4ca1af);
        font-family: 'Poppins', sans-serif;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.07);
        backdrop-filter: blur(12px);
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        transition: transform 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 40px;
    }

    .glass-card:hover {
        transform: translateY(-4px);
    }

    .card-header-custom {
        background: linear-gradient(to right, #1f1c2c, #928dab);
        color: white;
        padding: 1.25rem 1.5rem;
        font-size: 1.2rem;
        font-weight: 600;
        border-bottom: none;
    }

    .table thead th {
        background-color: #2c3e50;
        color: #fff;
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .table tbody td {
        vertical-align: middle;
        font-size: 0.92rem;
        color: #333;
    }

    .highlight-weekend {
        background-color: rgba(255, 193, 7, 0.15) !important;
    }

    .badge-day {
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 50rem;
        background: linear-gradient(to right, #00c9ff, #92fe9d);
        color: #000;
    }

    .table td strong {
        color: #28a745;
    }

    .note-text {
        font-size: 0.9rem;
        color: #f8f9fa;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        padding: 8px 12px;
        display: inline-block;
    }

    .collapse-row {
        background-color: rgba(255, 255, 255, 0.3);
    }

    .collapse-inner-table th {
        background-color: #444 !important;
        color: white;
    }

    .btn-outline-info {
        font-size: 0.8rem;
        padding: 4px 10px;
    }
</style>

<div class="container py-5">
    <!-- 🔹 Summary Card with Breakdown -->
    <div class="glass-card animate__animated animate__fadeIn">
        <div class="card-header-custom">
            <i class="bi bi-calendar-check"></i> Daily Income Summary - {{ $member->name }} ({{ $memid }})
        </div>

        <div class="card-body bg-light-subtle">
            @if($dailyIncome->count() > 0)
                <p class="note-text mb-3">
                    <i class="bi bi-info-circle-fill"></i> Income is <strong>not credited on Saturdays and Sundays</strong>.
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover rounded text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Received</th>
                                <th><i class="bi bi-clock-history"></i> History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyIncome as $index => $income)
                                @php
                                    $start = \Carbon\Carbon::createFromTimestamp($income->start_date)->timezone(config('app.timezone'));
                                    $end = \Carbon\Carbon::createFromTimestamp($income->end_date)->timezone(config('app.timezone'));
                                    $breakdowns = $incomeHistory->where('member_id', $income->member_id)
                                        ->whereBetween('date', [$income->start_date, $income->end_date]);
                                @endphp
                                <tr>
                                    <td>{{ $index + $dailyIncome->firstItem() }}</td>
                                    <td>{{ $start->format('d M Y') }}</td>
                                    <td>{{ $end->format('d M Y') }}</td>
                                    <td><strong>₹{{ number_format($income->total_received, 2) }}</strong></td>
                                    <td>
                                        @if($breakdowns->count() > 0)
                                            <button class="btn btn-sm btn-outline-info mt-1" data-bs-toggle="collapse" data-bs-target="#breakdown-{{ $income->id }}">
                                                <i class="bi bi-chevron-down"></i> View
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                @if($breakdowns->count() > 0)
                                    <tr class="collapse collapse-row" id="breakdown-{{ $income->id }}">
                                        <td colspan="5" class="p-0">
                                            <table class="table table-sm table-bordered mb-0 collapse-inner-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Day</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($breakdowns as $bIndex => $break)
                                                        @php
                                                            $bDate = \Carbon\Carbon::createFromTimestamp($break->date)->timezone(config('app.timezone'));
                                                            $bDay = $bDate->format('l');
                                                            $isWeekend = in_array($bDate->dayOfWeek, [6, 0]);
                                                        @endphp
                                                        <tr class="{{ $isWeekend ? 'highlight-weekend' : '' }}">
                                                            <td>{{ $bIndex + 1 }}</td>
                                                            <td>{{ $bDate->format('d M Y') }}</td>
                                                            <td><span class="badge-day">{{ $bDay }}</span></td>
                                                            <td><strong>₹{{ number_format($break->amount, 2) }}</strong></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $dailyIncome->links() }}
                </div>
            @else
                <div class="alert alert-info text-center mt-3">
                    No summary records found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
