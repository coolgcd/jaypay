{{-- resources/views/admin/payment_logs/index.blade.php --}}

@extends('admin.layout')
@section('title', 'Payment Logs')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Payment & Activity Logs</h4>
        <div class="text-end">
            <small class="text-muted">Total Records: {{ $logs->total() }}</small>
        </div>
    </div>

    {{-- Summary Cards --}}
    @if(isset($totals))
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h6 class="text-success">Total Credit</h6>
                    <h4 class="text-success">₹{{ number_format($totals['total_credit'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h6 class="text-danger">Total Debit</h6>
                    <h4 class="text-danger">₹{{ number_format($totals['total_debit'], 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h6 class="text-primary">Net Amount</h6>
                    <h4 class="text-primary">₹{{ number_format($totals['net_amount'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Filter Form --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-2">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="member_payment" {{ request('type') == 'member_payment' ? 'selected' : '' }}>Member Payment</option>
                <option value="tpin_issued" {{ request('type') == 'tpin_issued' ? 'selected' : '' }}>TPIN Issued</option>
                <option value="earning" {{ request('type') == 'income' ? 'selected' : '' }}>Member Income</option>
                <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <label class="form-label">Sub Type</label>
            <select name="sub_type" class="form-select">
                <option value="">All Sub Types</option>
                <option value="admin_credit" {{ request('sub_type') == 'admin_credit' ? 'selected' : '' }}>Admin Credit</option>
                <option value="member_debit" {{ request('sub_type') == 'member_debit' ? 'selected' : '' }}>Member Debit</option>
              <option value="daily_income" {{ request('sub_type') == 'daily_income' ? 'selected' : '' }}>Daily Income</option>
<option value="sponsor_daily_income" {{ request('sub_type') == 'direct_income' ? 'selected' : '' }}>Direct Income</option>
<option value="matching_income" {{ request('sub_type') == 'matching_income' ? 'selected' : '' }}>Matching Income</option>
<option value="salary_income" {{ request('sub_type') == 'salary_income' ? 'selected' : '' }}>Salary Income</option>
<option value="reward_income" {{ request('sub_type') == 'reward_income' ? 'selected' : '' }}>Reward Income</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Direction</label>
            <select name="direction" class="form-select">
                <option value="">All</option>
                <option value="credit" {{ request('direction') == 'credit' ? 'selected' : '' }}>Credit</option>
                <option value="debit" {{ request('direction') == 'debit' ? 'selected' : '' }}>Debit</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Member ID</label>
            <input type="text" name="member_id" class="form-control" value="{{ request('member_id') }}" placeholder="e.g. 11223344">
        </div>

        <div class="col-md-2">
            <label class="form-label">From Date</label>
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label">To Date</label>
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>

        <div class="col-md-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
           
        </div>
    </form>

    {{-- Logs Table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th width="12%">Date</th>
                    <th width="10%">Member ID</th>
                    <th width="10%">Type</th>
                    <th width="12%">Sub Type</th>
                    <th width="35%">Description</th>
                    <th width="8%" class="text-success">Credit</th>
                    <th width="8%" class="text-danger">Debit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration + ($logs->firstItem() - 1) }}</td>
                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <span class="badge bg-info">{{ $log->member_id }}</span>
                        @if($log->member)
                            <br><small class="text-muted">{{ $log->member->name ?? 'N/A' }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge 
                            @if($log->type == 'income') bg-success
                            @elseif($log->type == 'member_payment') bg-primary  
                            @elseif($log->type == 'tpin_issued') bg-warning
                            @elseif($log->type == 'withdrawal') bg-danger
                            @else bg-secondary @endif">
                            {{ strtoupper(str_replace('_', ' ', $log->type)) }}
                        </span>
                    </td>
                    <td>
                        <small class="text-muted">{{ strtoupper(str_replace('_', ' ', $log->sub_type)) }}</small>
                    </td>
                    <td class="text-start">
                        <strong>{{ $log->description }}</strong>
                        @if($log->remarks)
                            <br><small class="text-muted"><em>{{ $log->remarks }}</em></small>
                        @endif
                        @if($log->source)
                            <br><small class="badge bg-light text-dark">Source: {{ $log->source }}</small>
                        @endif
                    </td>
                    <td class="text-success text-end">
                        {{ $log->credit ? '₹' . number_format($log->credit, 2) : '-' }}
                    </td>
                    <td class="text-danger text-end">
                        {{ $log->debit ? '₹' . number_format($log->debit, 2) : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">
                        <em>No payment logs found</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $logs->appends(request()->query())->links() }}
    </div>
</div>
@endsection