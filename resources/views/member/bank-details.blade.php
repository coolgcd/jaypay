@extends('member.layout') {{-- Adjust based on your layout --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Your Bank Details</h4>
                    <span class="badge bg-success">Submitted</span>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> These details are read-only. Contact support if you need to make any changes.
                    </div>

                    <div class="row">
                        <!-- Account Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">Account Information</h5>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Account Holder Name</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->accname }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">Account Type</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->acctype }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">Account Number</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ 'XXXX' . substr($bankDetails->acc_number, -4) }}
                                    <small class="text-muted">(Last 4 digits shown for security)</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">Bank Name</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->bank_name }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">Branch</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->branch }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">IFSC Code</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->ifsc_code }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">Bank Address</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->address }}
                                </div>
                            </div>

                            @if($bankDetails->micr)
                            <div class="mb-3">
                                <label class="form-label text-muted">MICR Code</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->micr }}
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Personal & Payment Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">Personal Information</h5>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">PAN Number</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ substr($bankDetails->pannumber, 0, 2) . 'XXX' . substr($bankDetails->pannumber, -2) }}
                                    <small class="text-muted">(Partially hidden for security)</small>
                                </div>
                            </div>

                            @if($bankDetails->aadhar_number)
                            <div class="mb-3">
                                <label class="form-label text-muted">Aadhar Number</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ 'XXXX-XXXX-' . substr($bankDetails->aadhar_number, -4) }}
                                    <small class="text-muted">(Last 4 digits shown for security)</small>
                                </div>
                            </div>
                            @endif

                            <h5 class="mb-3 mt-4 text-primary">Digital Payment Information</h5>
                            
                            @if($bankDetails->googlepay)
                            <div class="mb-3">
                                <label class="form-label text-muted">Google Pay</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->googlepay }}
                                </div>
                            </div>
                            @endif

                            @if($bankDetails->phonepay)
                            <div class="mb-3">
                                <label class="form-label text-muted">PhonePe</label>
                                <div class="form-control-plaintext border rounded p-2 bg-light">
                                    {{ $bankDetails->phonepay }}
                                </div>
                            </div>
                            @endif

                            @if(!$bankDetails->googlepay && !$bankDetails->phonepay)
                            <div class="text-muted">
                                <em>No digital payment information provided</em>
                            </div>
                            @endif

                            <div class="alert alert-warning mt-4">
                                <h6><i class="fas fa-shield-alt"></i> Security Information</h6>
                                <ul class="mb-0 small">
                                    <li>Sensitive information is partially hidden</li>
                                    <li>Your data is encrypted and secure</li>
                                    <li>Contact support for any concerns</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('member.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                                </a>
                                
                                <button class="btn btn-outline-secondary" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print Details
                                </button>
                                
                                {{-- If you want to allow editing (remove one-time restriction) --}}
                                {{-- 
                                <a href="{{ route('member.bank.edit') }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i> Request Changes
                                </a>
                                --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .btn, .alert, .card-header .badge {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection