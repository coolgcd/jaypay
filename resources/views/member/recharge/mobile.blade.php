@extends('member.layout')
@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 col-xxl-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0 fw-bold">Mobile Recharge</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('member.recharge.mobile.submit') }}">
                        @csrf

                        <!-- Operator Selection -->
                        <div class="mb-4">
                            <label for="operator" class="form-label fw-semibold">
                                <i class="fas fa-network-wired me-2 text-primary"></i>Operator
                            </label>
                            <select class="form-select form-select-lg" id="operator" name="operator" required>
                                <option value="" disabled selected>Select Your Operator</option>
                                <option value="JIO">Jio</option>
                                <option value="BT">BSNL</option>
                                <option value="AT">Airtel</option>
                                <option value="VI">VI (Vodafone Idea)</option>

                            </select>
                        </div>

                        <!-- Mobile Number -->
                        <div class="mb-4">
                            <label for="mobile_number" class="form-label fw-semibold">
                                <i class="fas fa-mobile-alt me-2 text-primary"></i>Mobile Number
                            </label>
                            <input
                                type="tel"
                                class="form-control form-control-lg"
                                id="mobile_number"
                                name="mobile_number"
                                placeholder="Enter 10-digit mobile number"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                required>
                            <div class="form-text">Enter your 10-digit mobile number</div>
                        </div>

                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="amount" class="form-label fw-semibold">
                                <i class="fas fa-rupee-sign me-2 text-primary"></i>Recharge Amount
                            </label>
                            <input
                                type="number"
                                class="form-control form-control-lg"
                                id="amount"
                                name="amount"
                                placeholder="Enter amount"
                                min="10"
                                max="10000"
                                required>
                            <div class="form-text">Minimum ₹10, Maximum ₹10,000</div>
                        </div>

                        <!-- Quick Amount Buttons -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Quick Select Amount</label>
                            <div class="row g-2">
                                <div class="col-4 col-sm-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 quick-amount" data-amount="99">₹99</button>
                                </div>
                                <div class="col-4 col-sm-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 quick-amount" data-amount="199">₹199</button>
                                </div>
                                <div class="col-4 col-sm-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 quick-amount" data-amount="299">₹299</button>
                                </div>
                                <div class="col-4 col-sm-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 quick-amount" data-amount="399">₹399</button>
                                </div>
                                <div class="col-4 col-sm-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 quick-amount" data-amount="499">₹499</button>
                                </div>
                                <div class="col-4 col-sm-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 quick-amount" data-amount="999">₹999</button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="fas fa-bolt me-2"></i>Recharge Now
                            </button>
                        </div>
                    </form>
                </div>
                @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom responsive styles */
    @media (min-width: 992px) {
        .card-body {
            padding: 3rem !important;
        }

        .form-control,
        .form-select {
            font-size: 1.125rem;
            padding: 0.875rem 1rem;
        }

        .form-label {
            font-size: 1.1rem;
        }

        h4 {
            font-size: 2rem;
        }

        .quick-amount {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .btn-lg {
            font-size: 1.25rem;
            padding: 1rem 2rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .card {
            margin: 0;
            border-radius: 15px;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }

        .form-control,
        .form-select {
            font-size: 16px;
            /* Prevents zoom on iOS */
        }

        .quick-amount {
            font-size: 0.8rem;
            padding: 0.375rem 0.25rem;
        }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }

        h4 {
            font-size: 1.3rem;
        }
    }

    /* Enhanced form styling */
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .quick-amount:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }
</style>

<script>
    // Quick amount selection
    document.querySelectorAll('.quick-amount').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('amount').value = this.dataset.amount;

            // Remove active class from all buttons
            document.querySelectorAll('.quick-amount').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });

            // Add active class to clicked button
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
        });
    });

    // Mobile number validation
    document.getElementById('mobile_number').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });
</script>
@endsection