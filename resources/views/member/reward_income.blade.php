@extends('member.layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        background: linear-gradient(to right, #e3f2fd, #fce4ec);
        font-family: 'Poppins', sans-serif;
    }

    .reward-card {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        padding: 1.8rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        border: 3px solid transparent;
        background: linear-gradient(135deg, rgba(255,255,255,0.8), rgba(245,245,245,0.9));
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }

    .reward-card:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
    }

    .reward-card::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        z-index: -1;
        background: linear-gradient(225deg, #ffd700, #ff8a65, #81d4fa, #ab47bc);
        background-size: 300% 300%;
        animation: gradientBorder 6s ease infinite;
        border-radius: 22px;
    }

    @keyframes gradientBorder {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .reward-header {
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .reward-body p {
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .rank-badge {
        padding: 0.3rem 0.7rem;
        font-size: 0.85rem;
        border-radius: 1rem;
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
    }

    .gold { background: #FFD700; color: #000; }
    .silver { background: #C0C0C0; color: #000; }
    .bronze { background: #cd7f32; color: #fff; }
    .diamond { background: #b9f2ff; color: #000; }
    .platinum { background: #e5e4e2; color: #000; }

    .reward-cash {
        font-size: 1.6rem;
        font-weight: bold;
        background: linear-gradient(to right, #ff6f00, #ffd54f);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .status-badge {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 0.75rem;
        padding: 0.4rem 0.7rem;
        border-radius: 12px;
        color: #fff;
        backdrop-filter: blur(6px);
        font-weight: bold;
    }

    .claimed { background-color: rgba(76, 175, 80, 0.85); }
    .pending { background-color: rgba(255, 152, 0, 0.85); }

    .icon-rank {
        font-size: 1.4rem;
    }

</style>

@php
function getRank($rank) {
    return match(strtolower($rank)) {
        'gold' => ['class' => 'gold', 'icon' => '🥇'],
        'silver' => ['class' => 'silver', 'icon' => '🥈'],
        'bronze' => ['class' => 'bronze', 'icon' => '🥉'],
        'diamond' => ['class' => 'diamond', 'icon' => '💎'],
        'platinum' => ['class' => 'platinum', 'icon' => '⚪'],
        default => ['class' => 'silver', 'icon' => '🏅'],
    };
}
@endphp

<div class="container py-5">
    <div class="row text-center mb-4">
        <h2 class="animate__animated animate__fadeInDown">🏆 Premium Reward Income</h2>
        <p class="text-muted">Member: <strong>{{ $member->name }}</strong> (ID: {{ $memid }})</p>
    </div>

    @if($rewardIncome->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($rewardIncome as $reward)
                @php $rank = getRank($reward->rank); @endphp
                <div class="col animate__animated animate__zoomIn">
                    <div class="reward-card position-relative">
                        <!-- <div class="status-badge {{ $reward->status == 1 ? 'claimed' : 'pending' }}">
                            {{ $reward->status == 1 ? 'Claimed' : 'Pending' }}
                        </div> -->

                        <div class="reward-header">
                            <span>{{ $rank['icon'] }} Reward #{{ $reward->id }}</span>
                            <span class="rank-badge {{ $rank['class'] }}">{{ ucfirst($reward->rank) }}</span>
                        </div>

                        <div class="reward-body">
                            <p><strong>🔗 Matching Pair:</strong> {{ $reward->matching_pair }}</p>
                            <p><strong>💎 Rank:</strong> {{ ucfirst($reward->rank) }}</p>
                            <p><strong>💰 Cash:</strong> <span class="reward-cash">₹{{ number_format($reward->reward_cash, 2) }}</span></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $rewardIncome->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            No reward income records found for this member.
        </div>
    @endif
</div>
@endsection
