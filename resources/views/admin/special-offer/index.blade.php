@extends('admin.layout')
@section('title', 'Monsoon Offer Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
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


    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="fas fa-trophy fa-2x text-success mb-2"></i>
                    <h5 class="text-success">Total Achievements</h5>
                    <h3>{{ $stats['total_achievements'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>
                    <h5 class="text-info">Today's Achievements</h5>
                    <h3>{{ $stats['today_achievements'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x text-warning mb-2"></i>
                    <h5 class="text-warning">Unique Achievers</h5>
                    <h3>{{ $stats['unique_achievers'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $stats['offer_active'] ? 'border-success' : 'border-danger' }}">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-2x {{ $stats['offer_active'] ? 'text-success' : 'text-danger' }} mb-2"></i>
                    <h5 class="{{ $stats['offer_active'] ? 'text-success' : 'text-danger' }}">Offer Status</h5>
                    <h3>{{ $stats['offer_active'] ? $stats['days_remaining'] . ' Days Left' : 'Expired' }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            @if($stats['offer_active'])
            <form action="{{ route('admin.special.offer.process') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success btn-lg me-3" onclick="return confirm('Process Monsoon Offer for all eligible members?')">
                    <i class="fas fa-play"></i> Process Monsoon Offer
                </button>
            </form>
            @endif
            <a href="{{ route('admin.special.offer.report') }}" class="btn btn-info btn-lg">
                <i class="fas fa-chart-bar"></i> View Detailed Report
            </a>
        </div>
    </div>

    <!-- Sorting Controls -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-sort"></i> Sort Members with August Matching</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="available_pairs" {{ request('sort') == 'available_pairs' ? 'selected' : '' }}>Available Matching Pairs</option>
                                <option value="total_matching" {{ request('sort') == 'total_matching' ? 'selected' : '' }}>Total August Matching</option>
                                <option value="eligible_level" {{ request('sort') == 'eligible_level' ? 'selected' : '' }}>Eligible Level</option>
                                <option value="member_name" {{ request('sort') == 'member_name' ? 'selected' : '' }}>Member Name</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Direction</label>
                            <select name="direction" class="form-select">
                                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>High to Low</option>
                                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Low to High</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sort"></i> Apply Sort
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Members with August Matching Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-users"></i> Members with August Matching ({{ count($eligibleMembers) }})</h4>
                    <small>Showing all members with matching business > 0 from August 2025</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">Member ID</th>
                                    <th width="18%">Name</th>
                                    <th width="15%">August Matching</th>
                                    <th width="15%">Available Pairs</th>
                                    <th width="35%">Status / Eligible For</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eligibleMembers as $key => $member)
                                <tr class="{{ $member->eligible_level >= 1000000 ? 'table-success' : ($member->eligible_level >= 200000 ? 'table-warning' : 'table-light') }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td><span class="badge bg-primary">{{ $member->memid }}</span></td>
                                    <td><strong>{{ $member->member_name ?? 'N/A' }}</strong></td>
                                    <td><span class="badge bg-info">{{ number_format($member->total_matching_august) }}</span></td>
                                    <td><span class="badge bg-success">{{ number_format($member->available_pairs) }}</span></td>
                                    <td>
                                        @if($member->eligible_level >= 1000000)
                                            <span class="badge bg-success fs-6">🚗 {{ $member->eligible_for }}</span>
                                        @elseif($member->eligible_level >= 200000)
                                            <span class="badge bg-warning fs-6">🎁 {{ $member->eligible_for }}</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">💪 {{ $member->eligible_for }}</span>
                                            <br><small class="text-muted">Need {{ number_format(200000 - $member->available_pairs) }} more for first prize</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No members found with matching business in August</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Achievers -->
    @if(count($todayAchievers) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-star"></i> Today's New Achievers ({{ count($todayAchievers) }})</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Member ID</th>
                                    <th>Name</th>
                                    <th>Achievement</th>
                                    <th>Level</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAchievers as $key => $achiever)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><span class="badge bg-primary">{{ $achiever->member_id }}</span></td>
                                    <td>{{ $achiever->member_name ?? 'N/A' }}</td>
                                    <td><strong>{{ $achiever->rank }}</strong></td>
                                    <td><span class="badge bg-success">{{ number_format($achiever->matching_pair) }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($achiever->achieved_date)->format('H:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .table-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    .table-info {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }
</style>
@endsection
