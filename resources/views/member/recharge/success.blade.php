@extends('member.layout')
@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-10 col-lg-8 col-xl-6 col-xxl-5">
            <div class="card shadow-lg border-0 success-card">
                <div class="card-body text-center p-4 p-md-5">
                    <!-- Success Icon -->
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <!-- Success Message -->
                    <h3 class="text-success fw-bold mb-4">Recharge Successful!</h3>
                    
                    <!-- Transaction Details -->
                    <div class="transaction-details bg-light rounded-3 p-4 mb-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">
                                        <i class="fas fa-network-wired me-2"></i>Operator:
                                    </span>
                                    <span class="fw-bold text-uppercase">{{ $data['operator'] }}</span>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">
                                        <i class="fas fa-mobile-alt me-2"></i>Mobile Number:
                                    </span>
                                    <span class="fw-bold">{{ $data['number'] }}
</span>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">
                                        <i class="fas fa-rupee-sign me-2"></i>Amount:
                                    </span>
                                    <span class="fw-bold text-success fs-5">₹{{ $data['amount'] }}</span>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted fw-semibold">
                                        <i class="fas fa-receipt me-2"></i>Transaction ID:
                                    </span>
                                    <span class="fw-bold text-primary">{{ $data['txn_id'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="alert alert-info border-0 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Your recharge will be processed within 2-3 minutes. You will receive a confirmation SMS shortly.</small>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('member.recharge.mobile') }}" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-plus me-2"></i>Make Another Recharge
                        </a>
                        <a href="{{ route('member.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-home me-2"></i>Go to Dashboard
                        </a>
                    </div>
                    
                    <!-- Share Options -->
                    <div class="mt-4 pt-3 border-top">
                        <p class="text-muted mb-3">Share this recharge:</p>
                        <div class="d-flex justify-content-center gap-2">
                            <!-- <button class="btn btn-sm btn-outline-primary" onclick="shareViaWhatsApp()">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                            </button> -->
                            <button class="btn btn-sm btn-outline-info" onclick="copyTransactionId()">
                                <i class="fas fa-copy me-1"></i>Copy TXN ID
                            </button>
                            <!-- <button class="btn btn-sm btn-outline-success" onclick="downloadReceipt()">
                                <i class="fas fa-download me-1"></i>Receipt
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Success page specific styles */
    .success-card {
        border-radius: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
        overflow: hidden;
    }
    
    .success-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #28a745, #20c997, #17a2b8);
    }
    
    .success-icon {
        animation: successPulse 2s ease-in-out infinite;
    }
    
    @keyframes successPulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .transaction-details {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Desktop optimizations */
    @media (min-width: 992px) {
        .card-body {
            padding: 4rem 3rem !important;
        }
        
        .success-icon i {
            font-size: 5rem !important;
        }
        
        h3 {
            font-size: 2.5rem;
            margin-bottom: 2rem !important;
        }
        
        .transaction-details {
            padding: 2rem !important;
            margin-bottom: 2rem !important;
        }
        
        .d-flex.justify-content-between {
            padding: 0.75rem 0 !important;
        }
        
        .d-flex.justify-content-between span {
            font-size: 1.125rem;
        }
        
        .btn-lg {
            font-size: 1.25rem;
            padding: 1rem 2.5rem;
            min-width: 250px;
        }
        
        .alert {
            font-size: 1rem;
            padding: 1rem 1.5rem;
        }
        
        .btn-sm {
            font-size: 0.95rem;
            padding: 0.5rem 1.25rem;
        }
    }
    
    /* Mobile responsive adjustments */
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
        
        .success-icon i {
            font-size: 3rem !important;
        }
        
        h3 {
            font-size: 1.5rem;
        }
        
        .transaction-details {
            padding: 1rem !important;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.5rem;
        }
        
        .d-flex.justify-content-between span:last-child {
            margin-left: auto;
            font-size: 1.1rem;
        }
        
        .btn {
            font-size: 0.9rem;
        }
        
        .d-grid.gap-2 {
            gap: 0.75rem !important;
        }
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 2rem 1.5rem !important;
        }
        
        .d-md-flex {
            flex-direction: column !important;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
    }
    
    @media (min-width: 769px) {
        .d-md-flex .btn {
            min-width: 200px;
        }
    }
    
    /* Button hover effects */
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* Alert styling */
    .alert-info {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    /* Share buttons */
    .btn-sm {
        transition: all 0.2s ease;
    }
    
    .btn-sm:hover {
        transform: scale(1.05);
    }
</style>

<script>
    // Share via WhatsApp
    function shareViaWhatsApp() {
        const message = `🎉 Recharge Successful!\n\n📱 Mobile: {{ $data['number'] }}\n💰 Amount: ₹{{ $data['amount'] }}\n📋 Transaction ID: {{ $data['txn_id'] }}\n\nRecharged via Your App Name`;
        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }
    
    // Copy transaction ID
    function copyTransactionId() {
        const txnId = '{{ $data['txn_id'] }}';
        navigator.clipboard.writeText(txnId).then(function() {
            // Show success message
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-info');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-info');
            }, 2000);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
        });
    }
    
    // Download receipt (placeholder function)
    function downloadReceipt() {
        // You can implement actual receipt generation here
        alert('Receipt download feature will be implemented soon!');
    }
    
    // Add entrance animation
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.querySelector('.success-card');
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });
</script>
@endsection