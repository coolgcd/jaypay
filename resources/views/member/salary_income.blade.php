@extends('member.layout')

@section('content')
<style>
    .fade-in {
        animation: fadeIn 0.6s ease-in-out both;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .salary-card {
        border: 1px solid #e1e1e1;
        border-left: 5px solid #4CAF50;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
        background: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .salary-card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }

    .badge-slab {
        background-color: #2196F3;
        color: white;
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
    }

    .badge-amount {
        background-color: #4CAF50;
        color: white;
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
    }

    .salary-date {
        font-size: 14px;
        color: #555;
    }
</style>

<div class="container fade-in">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h3>💼 Salary Income for <strong>{{ $member->name }}</strong> ({{ $memid }})</h3>
        </div>

   @foreach($salaryIncome as $matchingAmount => $records)
<div class="col-md-12 fade-in mb-3">
    <div class="salary-card" data-bs-toggle="collapse" href="#group{{ $loop->index }}" role="button" aria-expanded="false" aria-controls="group{{ $loop->index }}" style="cursor: pointer;">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">💼 Matching Business: ₹{{ number_format($matchingAmount) }}</h5>
            <span class="badge bg-primary">Click to View</span>
        </div>
    </div>

    <div class="collapse mt-2" id="group{{ $loop->index }}">
        <div class="row">
            @foreach($records as $record)
            <div class="col-md-6">
                <div class="salary-card">
                    <h6>Salary ID #{{ $record->id }}</h6>
                    <p>💰 Amount: <strong class="text-success">₹{{ number_format($record->amount, 2) }}</strong></p>
                    <p>📅 From: {{ \Carbon\Carbon::createFromTimestamp($record->from_date)->format('d M Y') }}</p>
                    <p>📅 To: {{ \Carbon\Carbon::createFromTimestamp($record->to_date)->format('d M Y') }}</p>
                    @if($record->credited)
                        <span class="badge bg-success">Credited</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach



    </div>
</div>
@endsection
