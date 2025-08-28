@extends('member.layout')
@section('title', 'Monsoon Offer 2025')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card offer-header-card">
                <div class="card-body text-center py-4">
                    <div class="offer-icon mb-3">
                        <i class="fas fa-gift fa-3x text-white"></i>
                    </div>
                    <h2 class="text-white font-weight-bold mb-2">🌧️ Monsoon Offer 2025 🌧️</h2>
                    <p class="text-white mb-2 opacity-75">{{ $offerStats['offer_start_date'] }} - {{ $offerStats['offer_end_date'] }}</p>
                    @if($offerStats['offer_active'])
                        <div class="countdown-badge">
                            <span class="badge badge-warning badge-lg px-4 py-2">
                                <i class="fas fa-clock"></i> {{ $offerStats['days_remaining'] }} Days Left
                            </span>
                        </div>
                    @else
                        <span class="badge badge-danger badge-lg px-4 py-2">Offer Expired</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card progress-card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-chart-line"></i> Your Progress</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($memberProgress['total_matching']) }}</div>
                                    <div class="stat-label">Total August Matching</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($memberProgress['available_pairs']) }}</div>
                                    <div class="stat-label">Available Pairs</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="current-status">
                                @if($memberProgress['current_level'] > 0)
                                    <div class="achievement-badge achieved">
                                        <i class="fas fa-trophy"></i>
                                        <div class="achievement-text">
                                            <h5 class="mb-1">Current Achievement</h5>
                                            <p class="mb-0">{{ $memberProgress['current_prize'] }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="achievement-badge working">
                                        <i class="fas fa-target"></i>
                                        <div class="achievement-text">
                                            <h5 class="mb-1">Working Towards</h5>
                                            <p class="mb-0">{{ $memberProgress['next_prize'] }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @if($memberProgress['progress_percentage'] < 100)
                    <div class="progress-section mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Progress to next prize:</span>
                            <span class="font-weight-bold">{{ number_format($memberProgress['progress_percentage'], 1) }}%</span>
                        </div>
                        <div class="progress progress-animated">
                            <div class="progress-bar" style="width: {{ $memberProgress['progress_percentage'] }}%"></div>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Need {{ number_format($memberProgress['remaining_for_next']) }} more matching for 
                                <strong>{{ $memberProgress['next_prize'] }}</strong>
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Prize Levels -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-star"></i> Prize Levels</h4>
                </div>
                <div class="card-body p-0">
                    <div class="prize-timeline">
                        @foreach($specialOfferSlabs as $level => $prize)
                        <div class="timeline-item {{ $memberProgress['available_pairs'] >= $level ? 'achieved' : ($memberProgress['available_pairs'] >= ($level * 0.5) ? 'in-progress' : 'locked') }}">
                            <div class="timeline-marker">
                                @if($memberProgress['available_pairs'] >= $level)
                                    <i class="fas fa-check"></i>
                                @elseif($memberProgress['available_pairs'] >= ($level * 0.5))
                                    <i class="fas fa-clock"></i>
                                @else
                                    <i class="fas fa-lock"></i>
                                @endif
                            </div>
                            <div class="timeline-content">
                                <div class="prize-card">
                                    <div class="prize-header">
                                        <div class="prize-icon">
                                            <i class="{{ $prize['icon'] }}"></i>
                                        </div>
                                        <div class="prize-info">
                                            <h6 class="prize-level">{{ number_format($level) }} Matching Business</h6>
                                            <p class="prize-name">{{ $prize['rank'] }}</p>
                                        </div>
                                    </div>
                                    @if($memberProgress['available_pairs'] >= $level)
                                        <div class="achievement-status achieved">
                                            <i class="fas fa-trophy"></i> Achieved!
                                        </div>
                                    @elseif($level == $memberProgress['next_target'])
                                        <div class="achievement-status next">
                                            <i class="fas fa-target"></i> Next Target
                                            <div class="remaining-text">
                                                {{ number_format($level - $memberProgress['available_pairs']) }} more needed
                                            </div>
                                        </div>
                                    @else
                                        <div class="achievement-status locked">
                                            <i class="fas fa-lock"></i> Locked
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Achievements Section -->
    @if(count($memberAchievements) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-medal"></i> Your Achievements</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($memberAchievements as $achievement)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="achievement-card">
                                <div class="achievement-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="achievement-details">
                                    <h6 class="achievement-title">{{ $achievement->rank }}</h6>
                                    <p class="achievement-level">{{ number_format($achievement->matching_pair) }} Level</p>
                                    <small class="achievement-date">
                                        {{ Carbon\Carbon::parse($achievement->achieved_date)->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Custom Styles -->
<style>
/* Header Card with Gradient */
.offer-header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.offer-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.countdown-badge .badge {
    font-size: 1.1rem;
    border-radius: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Progress Card */
.progress-card {
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    border: none;
}

.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.stat-item {
    text-align: center;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #667eea;
    margin-bottom: 5px;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Achievement Badge */
.achievement-badge {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 10px;
    margin-top: 10px;
}

.achievement-badge.achieved {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.achievement-badge.working {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
}

.achievement-badge i {
    font-size: 2rem;
    margin-right: 15px;
}

/* Progress Bar */
.progress {
    height: 12px;
    border-radius: 10px;
    background-color: #e9ecef;
    overflow: hidden;
}

.progress-animated .progress-bar {
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 10px;
    transition: width 0.6s ease;
    animation: progress-animation 2s ease-in-out;
}

@keyframes progress-animation {
    0% { width: 0%; }
}

/* Prize Timeline */
.prize-timeline {
    padding: 20px;
}

.timeline-item {
    display: flex;
    margin-bottom: 20px;
    position: relative;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    flex-shrink: 0;
}

.timeline-item.achieved .timeline-marker {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.timeline-item.in-progress .timeline-marker {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: white;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.timeline-item.locked .timeline-marker {
    background: #e9ecef;
    color: #6c757d;
}

/* Prize Cards */
.prize-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    border-left: 4px solid transparent;
    flex: 1;
    transition: transform 0.3s ease;
}

.timeline-item.achieved .prize-card {
    border-left-color: #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
}

.timeline-item.in-progress .prize-card {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, #fffbf0 0%, #fff3cd 100%);
    transform: scale(1.02);
}

.timeline-item.locked .prize-card {
    border-left-color: #e9ecef;
    opacity: 0.6;
}

.prize-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.prize-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 1.5rem;
}

.prize-level {
    color: #667eea;
    font-weight: bold;
    margin-bottom: 5px;
}

.prize-name {
    color: #495057;
    margin-bottom: 0;
    font-size: 0.95rem;
}

/* Achievement Status */
.achievement-status {
    text-align: center;
    padding: 10px;
    border-radius: 8px;
    font-weight: bold;
}

.achievement-status.achieved {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
}

.achievement-status.next {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
}

.achievement-status.locked {
    background: #f8f9fa;
    color: #6c757d;
}

.remaining-text {
    font-size: 0.8rem;
    margin-top: 5px;
    opacity: 0.8;
}

/* Achievement Cards */
.achievement-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    transition: transform 0.3s ease;
    text-align: center;
}

.achievement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
}

.achievement-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #856404;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 1.5rem;
}

.achievement-title {
    color: #495057;
    font-weight: bold;
    margin-bottom: 5px;
}

.achievement-level {
    color: #667eea;
    font-weight: bold;
    margin-bottom: 5px;
}

.achievement-date {
    color: #6c757d;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .achievement-badge {
        flex-direction: column;
        text-align: center;
    }
    
    .achievement-badge i {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .prize-header {
        flex-direction: column;
        text-align: center;
    }
    
    .prize-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .timeline-item {
        flex-direction: column;
    }
    
    .timeline-marker {
        margin-right: 0;
        margin-bottom: 15px;
        align-self: center;
    }
}

/* Loading Animation */
.card {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hover Effects */
.card:hover {
    transition: transform 0.3s ease;
}

.progress-card:hover,
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 35px rgba(0,0,0,0.15);
}
</style>
@endsection
