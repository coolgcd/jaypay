@extends('member.layout')
@section('title', 'Dashboard')
@section('content')
@php
use App\Helpers\WalletHelper;

$earn = WalletHelper::getMemberEarnings($member->show_mem_id);
$package = $member->payment ?? 0;

$hasAtLeastOneActiveDirect = \DB::table('member')
->where('sponsorid', $member->show_mem_id)
->where('status', 1)
->exists();

$capPercent = $hasAtLeastOneActiveDirect ? 300 : 200;
$capAmount = $package * ($capPercent / 100);

if ($capAmount > 0) {
$progress = min(100, ($earn['total_income'] / $capAmount) * 100);
} else {
$progress = 0;
}

$remainingCap = max(0, $capAmount - $earn['total_income']);
@endphp

@if (session('success'))
    <div style="background-color: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="background-color: #f8d7da; color: #842029; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
        {{ session('error') }}
    </div>
@endif
@if($member->is_laps == 1)
<marquee behavior="scroll" direction="left" scrollamount="5"
 style="color: red; font-weight: bold; background-color: #fff3cd; padding: 10px; border: 1px solid #f5c6cb; border-radius: 22px; margin-bottom: 5px; font-family: 'Segoe UI', sans-serif;">
 ⚠️ Warning: Your earnings have reached the limit! Laps Amount: ₹{{ number_format($wallet['lapsamount'] ?? 0, 2) }} — Please upgrade or cap your account to continue earning. ⚠️
</marquee>
@elseif($member->active != 1)
<marquee behavior="scroll" direction="left" scrollamount="5"
 style="color: red; font-weight: bold; background-color: #fff3cd; padding: 10px; border: 1px solid #f5c6cb; border-radius: 22px; margin-bottom: 5px; font-family: 'Segoe UI', sans-serif;">
 🚫 Your account is currently inactive. Please contact support or take necessary actions to activate your account. 🚫
</marquee>
@endif

@if($progress >= 50 && $progress < 100)
    {{-- Marquee Warning with Recap Link --}}
    <marquee behavior="scroll" direction="left" scrollamount="5"
             style="color: #856404; font-weight: bold; background-color: #fff3cd; padding: 10px; border: 1px solid #ffeeba; border-radius: 22px; margin-top: 10px; font-family: 'Segoe UI', sans-serif;">
        ⚠️ Heads up! You’ve used {{ number_format($progress, 0) }}% of your earning limit (Capping). 
        Laps will apply once you reach ₹{{ number_format($capAmount, 2) }}. 
        <a href="{{ route('member.payment.create') }}" class="text-dark fw-bold text-decoration-underline">
            Click here to Recap
        </a> or consider upgrading your plan. ⚠️
    </marquee>
@endif

<div class="row g-4 mb-4">
    <!-- Profile Card -->
    <div class="col-lg-6 col-md-6">
        <div class="card dashboard-card profile-card">
            <div class="card-body text-white">
             <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Profile Information</h5>

                @php
                    if ($member->suspend == '1') {
                        $statusText = 'Suspended';
                        $statusClass = 'bg-danger text-white';
                    } elseif ($member->is_laps == '1') {
                        $statusText = 'Capped';
                        $statusClass = 'bg-warning text-dark';
                    } elseif ($member->active == '1') {
                        $statusText = 'Active';
                        $statusClass = 'bg-success text-white';
                    } else {
                        $statusText = 'Inactive';
                        $statusClass = 'bg-danger text-white';
                    }
                @endphp

                <div class="status-badge badge {{ $statusClass }}">
                    {{ $statusText }}
                </div>
            </div>

   
                <h4 class="member-name mb-3">{{ $member->name }}</h4>

                <div class="member-details">
                    <div class="detail-row">
                        <span class="detail-label">Package:</span>
                        <span class="detail-value">{{ $member->payment }} Rs</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Member ID:</span>
                        <span class="detail-value fw-bold">{{ $member->show_mem_id }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Sponsor ID:</span>
                        <span class="detail-value">{{ $member->sponsor->show_mem_id ?? 'N/A' }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Sponsor Name:</span>
                        <span class="detail-value">{{ $member->sponsor->name ?? 'N/A' }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Joined:</span>
                        <span class="detail-value">
                            {{ \Carbon\Carbon::parse($member->joindate)->format('d M Y') }}

                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Activation Date:</span>
                        <span class="detail-value">
                            {{ $member->activate_date && $member->activate_date > 0
    ? \Carbon\Carbon::createFromTimestamp($member->activate_date)->format('d M Y')
    : 'Not Activated' }}

                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Network Statistics Card -->
    <div class="col-lg-6 col-md-6">
        <div class="card dashboard-card network-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper me-3 bg-primary">
                        <i class="fa fa-sitemap text-white"></i>
                    </div>
                    <h5 class="card-title mb-0">Network Statistics</h5>
                </div>
                <div class="network-stats">
                    @foreach ([
                    ['label' => 'My Network', 'value' => $networkStats['my_network'] ?? 0, 'icon' => 'fa-users'],
                    ['label' => 'Direct Members', 'value' => $networkStats['direct_members'] ?? 0, 'icon' => 'fa-user-plus'],
                    ['label' => 'Active Direct', 'value' => $networkStats['active_direct'] ?? 0, 'icon' => 'fa-user-check'],
                    ['label' => 'Left Network', 'value' => $networkStats['left_network'] ?? 0, 'icon' => 'fa-arrow-left'],
                    ['label' => 'Right Network', 'value' => $networkStats['right_network'] ?? 0, 'icon' => 'fa-arrow-right'],
                    ['label' => 'Active Left', 'value' => $networkStats['active_left'] ?? 0, 'icon' => 'fa-chevron-left'],
                    ['label' => 'Active Right', 'value' => $networkStats['active_right'] ?? 0, 'icon' => 'fa-chevron-right']
                    ] as $stat)
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fa {{ $stat['icon'] }} text-primary"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-value">{{ number_format($stat['value']) }}</span>
                            <span class="stat-label">{{ $stat['label'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Earnings Summary Card -->
    <div class="col-lg-6 col-md-6">
        <div class="card dashboard-card earnings-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper me-3 bg-success">
                        <i class="fa fa-chart-line text-white"></i>
                    </div>
                    <h5 class="card-title mb-0">Earnings Summary</h5>
                </div>
                 <!-- <div class="card-body">
        @foreach($wallet['sources'] as $label => $amount)
            <p><strong>{{ $label }}:</strong> ₹{{ number_format($amount, 2) }}</p>
        @endforeach
    </div> -->
                <div class="earnings-list">
                    @php
                    $icons = [
                        'Daily Income' => 'fa-calendar-day',
                        'Direct Payment' => 'fa-hand-holding-usd',
                        'Matching Income' => 'fa-balance-scale',
                        'Salary' => 'fa-money-check',
                        'Reward' => 'fa-trophy',
                    ];
                @endphp

                @foreach($wallet['sources'] as $label => $amount)
                    <div class="earning-item">
                        <div class="earning-icon">
                            <i class="fa {{ $icons[$label] ?? 'fa-coins' }} text-success"></i>
                        </div>
                        <div class="earning-content">
                            <span class="earning-label">{{ $label }}</span>
                            <span class="earning-value">₹{{ number_format($amount, 2) }}</span>
                        </div>
                    </div>
                @endforeach

                    <div class="earning-item">
                        <div class="earning-icon">
                            <i class="fa fa-bullseye text-warning"></i>
                        </div>
                        <div class="earning-content">
                            <span class="earning-label">Capping Target ({{ $capPercent }}%)</span>
                            <span class="earning-value">₹{{ number_format($capAmount, 2) }}</span>
                        </div>
                    </div>

                    <div class="earning-item">
                        <div class="earning-icon">
                            <i class="fa fa-tachometer-alt text-warning"></i>
                        </div>
                  <div class="earning-content" style="width: 100%;">
                    <span class="earning-label">Capping Progress</span>

                    <div class="progress mt-1" style="height: 10px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                            style="width: {{ $progress }}%;"
                            aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <span class="earning-value d-block mt-1">
                        {{ number_format($progress, 2) }}% of ₹{{ number_format($capAmount, 2) }} cap
                    </span>

                @if ($progress >= 100)
                    <a href="{{ route('member.payment.create') }}" 
                    class="btn btn-warning btn-sm text-dark fw-semibold mt-2 d-inline-flex align-items-center gap-1 rounded-pill shadow-sm px-3 py-1">
                        <i class="bi bi-arrow-repeat"></i> Recap
                    </a>
                @endif

                </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Wallet Summary Card -->
    <div class="col-lg-6 col-md-6">
        <div class="card dashboard-card wallet-card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-wrapper me-3 bg-info">
                        <i class="fa fa-wallet text-white"></i>
                    </div>
                    <h5 class="card-title mb-0">Wallet Summary</h5>
                </div>
                @php
    $walletItems = [
        [
            'label' => 'Main Wallet',
            'value' => $wallet['balance'],
            'desc'  => 'Available Balance',
            'icon'  => 'fa-wallet'
        ],
        [
            'label' => 'Withdrawn',
            'value' => $wallet['total_withdrawn'],
            'desc'  => 'Total Withdrawn',
            'icon'  => 'fa-money-bill-wave'
        ],
        [
            'label' => 'Recharge',
            'value' => $wallet['recharge'],
            'desc'  => 'Total Recharges',
            'icon'  => 'fa-money-bill-wave'
        ],
        [
            'label' => 'Total Earnings',
            'value' => $wallet['total_income'],
            'desc'  => 'All Income Sources',
            'icon'  => 'fa-money-check-alt'
        ],
        [
            'label' => 'Remaining Cap',
            'value' => max($remainingCap ?? 0, 0),
            'desc'  => 'Earning headroom left',
            'icon'  => 'fa-balance-scale-left'
        ],
    ];
@endphp

<div class="wallet-list">
    @foreach($walletItems as $item)
    <div class="wallet-item">
        <div class="wallet-icon">
            <i class="fa {{ $item['icon'] }} text-info"></i>
        </div>
        <div class="wallet-content">
            <div class="wallet-main">
                <span class="wallet-label">{{ $item['label'] }}</span>
                <span class="wallet-value">₹{{ number_format($item['value'], 2) }}</span>
            </div>
            <span class="wallet-desc">{{ $item['desc'] }}</span>
        </div>
    </div>
    @endforeach
</div>







            </div>

        </div>


    </div>


    @php
    $referralLink = route('member.register', ['sponsor' => auth()->user()->show_mem_id]);
    @endphp



    

<div style="margin-top: 20px;">
    <label><strong>Your Referral Link</strong></label>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <input
            type="text"
            id="referralLink"
            value="{{ $referralLink }}"
            readonly
            style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 5px; min-width: 200px;">
        <button
            type="button"
            onclick="copyReferralLinkWithPosition('left')"
            style="padding: 8px 15px; background: #4FD1C5; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Copy Left Link
        </button>
        <button
            type="button"
            onclick="copyReferralLinkWithPosition('right')"
            style="padding: 8px 15px; background: #4FD1C5; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Copy Right Link
        </button>
    </div>
</div>

<script>
function copyReferralLinkWithPosition(position) {
    const baseLink = document.getElementById('referralLink').value;
    const separator = baseLink.includes('?') ? '&' : '?';
    const fullLink = `${baseLink}${separator}position=${position}`;

    navigator.clipboard.writeText(fullLink).then(() => {
        alert(`Copied link with position=${position}: \n${fullLink}`);
    }).catch(err => {
        console.error('Failed to copy link: ', err);
    });
}
</script>


    <!-- <div style="margin-top: 20px;">
        <label><strong>Your Referral Link</strong></label>
        <div style="display: flex; gap: 10px;">
            <input
                type="text"
                id="referralLink"
                value="{{ $referralLink }}"
                readonly
                style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
            <button
                type="button"
                onclick="copyReferralLink()"
                style="padding: 8px 15px; background: #4FD1C5; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Copy
            </button>
        </div>
    </div>

</div>

<script>
    function copyReferralLink() {
        const input = document.getElementById('referralLink');
        input.select();
        input.setSelectionRange(0, 99999); // for mobile
        document.execCommand('copy');
        alert('Referral link copied to clipboard!');
    }
</script> -->

<style>
    /* Enhanced Dashboard Styles with Better Card Visibility */
    .dashboard-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    .card-title {
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
    }

    /* Profile Card Styles */
    .profile-card {
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        /* background: linear-gradient(135deg,  #2c3e50 0%, #bdc3c7 140%); */
        /* background: #2c3e50; */
         /* background: #7879FF;
        color: white; */
          background: linear-gradient(135deg, #ef4136, #fbb040);
         color: #fff;
         filter: brightness(0.9) saturate(0.7);

    }

    .profile-card .card-title {
        /* color: white; */
        color: #ffffff;
    }

    .member-name {
          color: #ffffff;
        font-weight: 900;
        font-size: 1.5rem;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background-color: red;
    }

    .member-details {
        background: rgba(255, 255, 255, 0.1);
        padding: 1.2rem;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .detail-row:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .detail-label {
    font-weight: 600;
    font-size: 1rem;
    color: #ffffff;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

  .detail-value {
    font-weight: 700;
    font-size: 1rem;
    color: #ffffff;
}
    /* Network Card Styles */
    .network-card {
        /* background: linear-gradient(135deg, #ffafbd 0%, #ffc3a0 100%); */
        /* background: linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%); */
        /* background: #2c3e50; */
         /* background:#1a1a1a ; */
             background: linear-gradient(135deg, #ef4136, #fbb040);
         color: #fff;
         filter: brightness(0.9) saturate(0.7);

    }

    .network-card .card-title {
        color: white;
    }

    .network-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }


    .stat-item {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.8rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

/* Icon stays same */
.stat-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.8rem;
}

/* Container for text */
.stat-content {
    display: flex;
    flex-direction: column;
}

/* Value styling: bigger, bolder, brighter */
.stat-value {
    font-size: 1.3rem;        /* slightly larger */
    font-weight: 800;         /* extra bold */
    color: #ffffff;           /* solid white */
    line-height: 1;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);  /* optional: improves contrast */
}

/* Label styling: brighter and bolder */
.stat-label {
    font-size: 0.85rem;        /* slightly larger for legibility */
    font-weight: 600;          /* bolder text */
    color: rgba(255, 255, 255, 0.95); /* almost solid white */
    margin-top: 0.2rem;
}


    /* .stat-item {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.8rem;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .stat-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.8rem;
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 0.2rem;
    } */

    /* Earnings Card Styles */
    .earnings-card {
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        /* background: linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%); */
        /* background: #2c3e50;
        color: white; */

          background: linear-gradient(135deg, #ef4136, #fbb040);
         color: #fff;
         filter: brightness(0.9) saturate(0.7);

    }

    .earnings-card .card-title {
        color: white;
    }



    .earnings-list {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.earning-item {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.8rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.earning-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.8rem;
}

.earning-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

/* Label styling: bigger, bolder, brighter */
.earning-label {
    color: rgba(255, 255, 255, 0.95);  /* almost solid white */
    font-size: 1rem;                   /* slightly larger */
    font-weight: 600;                  /* bold */
}

/* Value styling: biggest, extra bold, crisp */
.earning-value {
    color: #ffffff;                    /* solid white */
    font-weight: 800;                  /* extra bold */
    font-size: 1.2rem;                 /* larger size */
    text-shadow: 0 1px 2px rgba(0,0,0,0.3); /* optional: improves readability */
}


    /* .earnings-list {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .earning-item {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.8rem;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .earning-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.8rem;
    }

    .earning-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .earning-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
    }

    .earning-value {
        color: white;
        font-weight: 700;
        font-size: 1rem;
    } */

    /* Wallet Card Styles */
    .wallet-card {
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        /* background: linear-gradient(135deg, #bdc3c7 0%, #2c3e50 100%); */
        /* background: #2c3e50;
        color: white; */
          background: linear-gradient(135deg, #ef4136, #fbb040);
         color: #fff;
         filter: brightness(0.9) saturate(0.7);

    }

    .wallet-card .card-title {
        color: white;
    }


    .wallet-item {
    display: flex;
    align-items: center; /* vertical centering */
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.wallet-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem; /* KEEP this margin */
    flex-shrink: 0; /* ensures it won't scale down */
}

.wallet-icon i {
    color: #00bcd4; /* or #ffffff if you prefer */
    font-size: 1.2rem; /* keep same size */
}

.wallet-content {
    flex: 1;
}

.wallet-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.3rem;
}

.wallet-label {
    color: rgba(255, 255, 255, 0.95);
    font-weight: 700;
    font-size: 1.1rem;
    line-height: 1.3; /* ensure no overlapping */
}

.wallet-value {
    color: #ffffff;
    font-weight: 800;
    font-size: 1.3rem;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.wallet-desc {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    font-size: 0.9rem;
}


    /* .wallet-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .wallet-item {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .wallet-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .wallet-content {
        flex: 1;
    }

    .wallet-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.3rem;
    }

    .wallet-label {
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }

    .wallet-value {
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .wallet-desc {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8rem;
    } */

    /* Icon Wrapper */
    .icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .network-stats {
            grid-template-columns: 1fr;
        }

        .stat-item,
        .earning-item,
        .wallet-item {
            padding: 0.6rem;
        }

        .member-details {
            padding: 1rem;
        }
    }

    /* Background color fix for better card visibility */
    body {
        /* background-color: #f8f9fa !important; */
        background-color: #e7e4e2;
    }

    .container-fluid {
        background-color: #f8f9fa;
    }
</style>
@endsection