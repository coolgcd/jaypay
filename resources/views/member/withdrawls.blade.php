@extends('member.layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Withdrawal History - {{ $member->name }} ({{ $member->show_mem_id }})</h4>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('member.withdrawals', $member->show_mem_id) }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search">Search</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ $search ?? '' }}" 
                                           placeholder="Transaction ID, Account, Amount...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                                        <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ ($status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ ($status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_from">From Date</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_from" 
                                           name="date_from" 
                                           value="{{ $date_from ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_to">To Date</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_to" 
                                           name="date_to" 
                                           value="{{ $date_to ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                        <a href="{{ route('member.withdrawals', $member->show_mem_id) }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if($withdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Request Date</th>
                                        <th>Total Income</th>
                                        <th>Level Income</th>
                                        <th>Total Withdraw</th>
                                        <th>Current Balance</th>
                                        <th>Withdraw Amount</th>
                                        <th>Deduction</th>
                                        <th>Final Amount</th>
                                        <th>Status</th>
                                        <th>Transaction ID</th>
                                        <th>Account Details</th>
                                        <th>Confirmed Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $index => $withdrawal)
                                        <tr>
                                            <td>{{ ($withdrawals->currentPage() - 1) * $withdrawals->perPage() + $index + 1 }}</td>
                                            <td>
                                                @if($withdrawal->request_date)
                                                    {{ date('d-m-Y H:i:s', $withdrawal->request_date) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>₹{{ number_format($withdrawal->tot_s_income, 2) }}</td>
                                            <td>₹{{ number_format($withdrawal->tot_level_income, 2) }}</td>
                                            <td>₹{{ number_format($withdrawal->tot_withdraw, 2) }}</td>
                                            <td>₹{{ number_format($withdrawal->tot_balance, 2) }}</td>
                                            <td>₹{{ number_format($withdrawal->cur_withdraw_amt, 2) }}</td>
                                            <td>₹{{ number_format($withdrawal->deduction, 2) }}</td>
                                            <td>₹{{ number_format($withdrawal->final_amt, 2) }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($withdrawal->status == 'approved') badge-success
                                                    @elseif($withdrawal->status == 'pending') badge-warning
                                                    @elseif($withdrawal->status == 'rejected') badge-danger
                                                    @else badge-secondary
                                                    @endif">
                                                    {{ ucfirst($withdrawal->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $withdrawal->transaction_id ?? '-' }}</td>
                                            <td>
                                                @if($withdrawal->accountname || $withdrawal->accountno)
                                                    <strong>Name:</strong> {{ $withdrawal->accountname }}<br>
                                                    <strong>A/c No:</strong> {{ $withdrawal->accountno }}<br>
                                                    <strong>IFSC:</strong> {{ $withdrawal->ifsccode }}<br>
                                                    <strong>Mobile:</strong> {{ $withdrawal->mobileno }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($withdrawal->confirmt_date)
                                                    {{ date('d-m-Y H:i:s', $withdrawal->confirmt_date) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted">
                                    Showing {{ $withdrawals->firstItem() }} to {{ $withdrawals->lastItem() }} of {{ $withdrawals->total() }} results
                                    @if($search || $status != 'all' || $date_from || $date_to)
                                        | <strong>Filtered Results</strong>
                                        @if($search) | Search: "{{ $search }}" @endif
                                        @if($status && $status != 'all') | Status: {{ ucfirst($status) }} @endif
                                        @if($date_from) | From: {{ $date_from }} @endif
                                        @if($date_to) | To: {{ $date_to }} @endif
                                    @endif
                                </p>
                            </div>
                            <div>
                                {{ $withdrawals->links() }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            @if($search || $status != 'all' || $date_from || $date_to)
                                No withdrawal records found matching your search criteria.
                                <a href="{{ route('member.withdrawals', $member->show_mem_id) }}" class="btn btn-sm btn-outline-primary ml-2">Clear Filters</a>
                            @else
                                No withdrawal records found for this member.
                            @endif
                        </div>
                    @endif
                </div>
       
            </div>
        </div>
    </div>
</div>
@endsection