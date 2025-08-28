@extends('admin.layout')
@section('title', 'Monsoon Offer Report')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-center py-3">
                <h4 class="text-white mb-2"><i class="fas fa-gift"></i> JayPay Monsoon Offer</h4>
                <p class="text-white mb-1 opacity-75">01 August 2025 to 15 September 2025</p>
                <small class="text-white opacity-50">Special Limited Time Prize Tracking</small>
            </div>
        </div>
    </div>
</div>
    <!-- Level-wise Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-trophy"></i> Level-wise Achievement Stats</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($levelStats as $stat)
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="text-success">{{ number_format($stat->matching_pair) }} Level</h6>
                                    <h4 class="text-success">{{ $stat->total_achievers }}</h4>
                                    <small class="text-muted">{{ $stat->rank }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Achievements</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from', '2025-08-01') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Achievement Level</label>
                            <select name="level" class="form-select">
                             <option value="5000000" {{ request('level') == '5000000' ? 'selected' : '' }}>50 Lakh (Scorpio N)</option>
<option value="4000000" {{ request('level') == '4000000' ? 'selected' : '' }}>40 Lakh (Tata Curve)</option>
<option value="3000000" {{ request('level') == '3000000' ? 'selected' : '' }}>30 Lakh (Mahindra XUV)</option>
<option value="2000000" {{ request('level') == '2000000' ? 'selected' : '' }}>20 Lakh (Tata Punch)</option>
<option value="1500000" {{ request('level') == '1500000' ? 'selected' : '' }}>15 Lakh (Swift VDI)</option>
<option value="1000000" {{ request('level') == '1000000' ? 'selected' : '' }}>10 Lakh (WagonR)</option>
<option value="700000" {{ request('level') == '700000' ? 'selected' : '' }}>7 Lakh (Bike Splendor Plus)</option>
<option value="500000" {{ request('level') == '500000' ? 'selected' : '' }}>5 Lakh (Andaman Tour)</option>
<option value="200000" {{ request('level') == '200000' ? 'selected' : '' }}>2 Lakh (Washing Machine)</option>

                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('admin.special.offer.report') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Achievements Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-list"></i> All Achievements ({{ $achievements->total() }})</h4>
                    <small>Showing achievements from August 2025 onwards</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Member ID</th>
                                    <th width="20%">Name</th>
                                    <th width="15%">Achievement Level</th>
                                    <th width="30%">Prize</th>
                                    <th width="18%">Achieved Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($achievements as $key => $achievement)
                                <tr class="{{ $achievement->matching_pair >= 1000000 ? 'table-success' : ($achievement->matching_pair >= 500000 ? 'table-warning' : 'table-info') }}">
                                    <td>{{ ($achievements->currentPage() - 1) * $achievements->perPage() + $key + 1 }}</td>
                                    <td><span class="badge bg-primary">{{ $achievement->member_id }}</span></td>
                                    <td><strong>{{ $achievement->member_name ?? 'N/A' }}</strong></td>
                                    <td>
                                        <span class="badge bg-dark">{{ number_format($achievement->matching_pair) }}</span>
                                    </td>
                                    <td>
                                        @if($achievement->matching_pair >= 1000000)
                                            <span class="badge bg-success fs-6">🚗 {{ $achievement->rank }}</span>
                                        @elseif($achievement->matching_pair >= 500000)
                                            <span class="badge bg-warning fs-6">🏍️ {{ $achievement->rank }}</span>
                                        @else
                                            <span class="badge bg-info fs-6">📱 {{ $achievement->rank }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            {{ \Carbon\Carbon::parse($achievement->achieved_date)->format('d M Y H:i A') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No achievements found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $achievements->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
