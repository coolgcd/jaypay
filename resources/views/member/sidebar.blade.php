{{-- Add this PHP check at the top of your sidebar --}}
@php
    $hasBankDetails = \App\Models\MemberBankDetail::where('member_id', auth()->user()->show_mem_id)->exists();
@endphp

<div class="sidebar" id="sidebar">
    <!-- Logo Section -->
    <div class="sidebar-logo">
        <img src="{{ asset('assets/images/logo.jpeg') }}" alt="Logo" class="logo-img">
    </div>

    <!-- Header Section -->
    <!-- <div class="sidebar-header">
        <h4 class="sidebar-header-text">Member Portal</h4>
        <i class="fa fa-user-circle sidebar-icon"></i>
    </div> -->
    
    <!-- Navigation Section -->
    <div class="sidebar-nav">
        <!-- Dashboard -->
        <a href="{{ route('member.dashboard') }}" class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt"></i>
            <span class="text-label">Dashboard</span>
        </a>
        
        <!-- Profile Dropdown -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-user"></i>
                <span class="text-label">Profile</span>
            </a>
            <ul class="dropdown-menu shadow" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="{{ route('member.profile') }}">My Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('member.profile.edit') }}">Edit Profile</a></li>
                
                {{-- Bank Details Integration --}}
                @if(!$hasBankDetails)
                    <li>
                        <a class="dropdown-item text-warning" href="{{ route('member.bank.form') }}">
                            <i class="fas fa-university"></i> Submit Bank Details
                            <span class="badge bg-warning text-dark ms-1">!</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a class="dropdown-item" href="{{ route('member.bank.view') }}">
                            <i class="fas fa-university"></i> View Bank Details
                            <span class="badge bg-success ms-1">✓</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Associates Dropdown -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="associatesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-globe"></i>
                <span class="text-label">My Associates</span>
            </a>
            <ul class="dropdown-menu shadow" aria-labelledby="associatesDropdown">
                <li><a class="dropdown-item" href="{{ route('member.directAssociates') }}">Direct Associates</a></li>
                <li><a class="dropdown-item" href="{{ route('member.associateNetwork') }}">Associates Network</a></li>
            </ul>
        </div>

        <!-- Network Details Dropdown -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="networkDetailsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-project-diagram"></i>
                <span class="text-label">Network Details</span>
            </a>
            <ul class="dropdown-menu shadow" aria-labelledby="networkDetailsDropdown">
                <li><a class="dropdown-item" href="{{ route('member.tree') }}">My Network Tree View</a></li>
                <li><a class="dropdown-item" href="{{ route('member.left_network') }}">My Left Network</a></li>
                <li><a class="dropdown-item" href="{{ route('member.right_network') }}">My Right Network</a></li>
            </ul>
        </div>

        <!-- Income Dropdown -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="myIncome" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-wallet"></i>
                <span class="text-label">My Income</span>
            </a>
            <ul class="dropdown-menu shadow" aria-labelledby="myIncome">
               <li>
    <a class="dropdown-item" href="{{ route('member.daily.income') }}">
        Daily Income
    </a>
</li>
                <li>
    <a class="dropdown-item" href="{{ route('member.direct.payment') }}">
        Direct Income
    </a>
</li>

<li>
    <a class="dropdown-item" href="{{ route('member.matching.income') }}">
        Matching Income
    </a>
</li>
                    <li><a class="dropdown-item" href="{{ route('member.salary.income') }}">Salary Income</a>
</li>
                <li><a class="dropdown-item" href="{{ route('member.reward.index') }}">Reward Income</a>
</li>
            </ul>
        </div>
        <!-- Member Payments -->

<a href="{{ route('member.monsoon.offer') }}" class="nav-link">
    <i class="fas fa-gift"></i> 
    <span class="text-label">Monsoon Offer</span>
</a>

        
<!-- Payment Section -->
<div class="nav-item dropdown">
<a href="#" class="nav-link dropdown-toggle" id="paymentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fas fa-wallet"></i>
<span class="text-label">Payments</span>
</a>
<ul class="dropdown-menu shadow" aria-labelledby="paymentDropdown">
<li><a class="dropdown-item" href="{{ route('member.payment.create') }}">New Payment</a></li>
<li><a class="dropdown-item" href="{{ route('member.payment.history') }}">Payment History</a></li>
</ul>
</div>


        <!-- Topup Pins Dropdown -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="topupDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bolt"></i>
                <span class="text-label">Topup Pins</span>
            </a>
            <ul class="dropdown-menu shadow" aria-labelledby="topupDropdown">
                <li><a class="dropdown-item" href="{{ route('member.topup.pin') }}">Available Pins</a></li>
                <li><a class="dropdown-item" href="{{ route('member.topup.used') }}">Used Pins</a></li>
            </ul>
        </div>

        <!-- Withdrawals -->
        <!-- <a href="{{ route('member.withdrawals', auth()->user()->show_mem_id) }}" class="nav-link {{ request()->routeIs('member.withdrawals') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i>
            <span class="text-label">Withdrawals</span>
        </a> -->
    <a href="{{ route('member.utility') }}" class="nav-link">
        <i class="fas fa-bolt"></i> 
          <span class="text-label">Utility</span>
    </a>
    <a href="{{ route('member.recharge.history') }}" class="nav-link">
    <i class="fas fa-history"></i>
    <span class="text-label">Recharge History</span>
</a>
    <a href="{{ route('member.payment-history') }}" class="nav-link">
    <i class="fas fa-file-invoice-dollar"></i>
    <span class="text-label">Payment History</span>
</a>

<a href="{{ route('member.withdraw.index') }}" class="nav-link">
    <i class="fas fa-university"></i>
    <span class="text-label">Withdraw Request</span>
</a>
<a href="{{ route('member.password.edit') }}" class="nav-link">
    <i class="fas fa-key"></i> 
    <span class="text-label">Change Password</span>
</a>
        <!-- Logout -->
            <form action="{{ route('member.logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-light" style="padding: 0.75rem 1rem;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="text-label">Logout</span>
                </button>
            </form>
        
    </div>
</div>