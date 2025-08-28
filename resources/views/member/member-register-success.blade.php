@extends('layouts.front')

@section('content')
<style>
    .success-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        background: linear-gradient(135deg, #f0f0f0, #ffffff);
    }

    .success-card {
        background: #fff;
        border-radius: 20px;
        padding: 50px 40px;
        text-align: center;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        max-width: 600px;
margin: 7% 0 0 0;
        width: 100%;
        border: 2px solid #e5d5a4;
        position: relative;
    }

    .success-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 8px;
        background: linear-gradient(to right, #c9a74d, #f6e58d, #c9a74d);
        border-radius: 0 0 8px 8px;
    }

    .success-card img {
        width: 100px;
        border-radius: 50%;
        margin-bottom: 20px;
        border: 3px solid #c9a74d;
    }

    .success-card h2 {
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 1.8rem;
        color: #333;
    }

    .success-card p {
        font-size: 1rem;
        color: #555;
        margin-bottom: 8px;
    }

    .success-card h3 {
        color: #27ae60;
        font-size: 2rem;
        margin: 10px 0 20px;
        font-weight: 700;
    }

    .success-card .submit-btn {
        display: inline-block;
        padding: 12px 30px;
        background: #c9a74d;
        color: #fff;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        transition: background 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .success-card .submit-btn:hover {
        background: #a88b3c;
    }

    @media (max-width: 576px) {
        .success-card {
            padding: 35px 20px;
        }

        .success-card h2 {
            font-size: 1.5rem;
        }

        .success-card h3 {
            font-size: 1.6rem;
        }

        .success-card .submit-btn {
            padding: 10px 24px;
            font-size: 0.95rem;
        }
    }
</style>

<div class="success-wrapper">
    <div class="success-card">
        <img src="{{ asset('assets/images/logo.jpeg') }}" alt="JayPay Logo">

        <h2>🎉 Welcome to JayPay!</h2>

        @if(!empty($memberName))
            <p>Hello <strong>{{ ucfirst($memberName) }}</strong>,</p>
        @endif

        <p>Your registration was <strong>successful</strong>.</p>

        <p>Your Member ID:</p>
        <h3>{{ $showMemId }}</h3>

        @if(!empty($memberEmail))
            <p>Registered Email: <strong>{{ $memberEmail }}</strong></p>
        @endif

        <p>Date: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>

        <div style="margin-top: 25px;">
            <a href="{{ route('member.login') }}" class="submit-btn">Proceed to Login</a>
        </div>
    </div>
</div>
@endsection
