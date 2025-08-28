@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Member Details Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user"></i> Member Details
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>

                            
                            <tr>
                                    <th scope="row" class="fw-bold text-muted">Package:</th>
                                    <td>{{ $member->payment }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Member ID:</th>
                                    <td>{{ $member->show_mem_id }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Name:</th>
                                    <td>{{ $member->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Father's Name:</th>
                                    <td>{{ $member->father_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Gender:</th>
                                    <td>{{ $member->gender ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Email:</th>
                                    <td>{{ $member->emailid }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Mobile:</th>
                                    <td>{{ $member->mobileno }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Date of Birth:</th>
                                    <td>{{ $member->dob ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Sponsor ID:</th>
                                    <td>{{ $member->sponsorid }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Join Date:</th>
                                    <td>{{ $member->joindate ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Activation Date:</th>
                                    <td>{{ $member->activate_date ? date('Y-m-d H:i:s', $member->activate_date) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">PAN Number:</th>
                                    <td>{{ $member->pannumber ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Address:</th>
                                    <td>{{ $member->address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">City:</th>
                                    <td>{{ $member->city ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">State:</th>
                                    <td>{{ $member->state ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Pincode:</th>
                                    <td>{{ $member->pincode ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Status:</th>
                                    <td>
                                        @if($member->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Income Summary Card -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-chart-line"></i> Income Summary
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Daily Income:</th>
                                    <td class="text-success fw-bold">₹{{ number_format($dailyIncome, 2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Direct Payment:</th>
                                    <td class="text-success fw-bold">₹{{ number_format($directIncome, 2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Matching Income:</th>
                                    <td class="text-success fw-bold">₹{{ number_format($matchingIncome, 2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Salary:</th>
                                    <td class="text-success fw-bold">₹{{ number_format($salaryIncome, 2) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="fw-bold text-muted">Reward:</th>
                                    <td class="text-success fw-bold">₹{{ number_format($rewardIncome, 2) }}</td>
                                </tr>
                                <tr class="border-top">
                                    <th scope="row" class="fw-bold text-dark">Total Income:</th>
                                    <td class="text-primary fw-bold fs-5">
                                        ₹{{ number_format($dailyIncome + $directIncome + $matchingIncome + $salaryIncome + $rewardIncome, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bank Details Card -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-university"></i> Bank Details
                    </h4>
                </div>
                <div class="card-body">
                    @if($bankDetails)
                        <div class="row">
                            <div class="col-lg-6 col-md-12 mb-3">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Account Name:</th>
                                                <td>{{ $bankDetails->accname ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Account Type:</th>
                                                <td>{{ $bankDetails->acctype ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Account Number:</th>
                                                <td>{{ $bankDetails->acc_number ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Bank Name:</th>
                                                <td>{{ $bankDetails->bank_name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Branch:</th>
                                                <td>{{ $bankDetails->branch ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">IFSC Code:</th>
                                                <td>{{ $bankDetails->ifsc_code ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 mb-3">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Bank Address:</th>
                                                <td>{{ $bankDetails->address ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">MICR Code:</th>
                                                <td>{{ $bankDetails->micr ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">PAN Number:</th>
                                                <td>{{ $bankDetails->pannumber ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Aadhar Number:</th>
                                                <td>{{ $bankDetails->aadhar_number ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Google Pay:</th>
                                                <td>{{ $bankDetails->googlepay ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row" class="fw-bold text-muted">Phone Pay:</th>
                                                <td>{{ $bankDetails->phonepay ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            No bank details found for this member.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="button" class="btn btn-primary me-2" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                   <a href="{{ route('admin.member.edit', $member->show_mem_id) }}" class="btn btn-info">
    <i class="fas fa-edit"></i> Edit Member
</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for member view */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.375rem;
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
    width: 40%;
    padding: 0.75rem 0.5rem;
    border: none !important;
}

.table td {
    padding: 0.75rem 0.5rem;
    border: none !important;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Print styles */
@media print {
    .btn, .card-header {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table th, .table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.875rem;
    }
    
    .card-title {
        font-size: 1.1rem;
    }
    
    .btn {
        margin-bottom: 0.5rem;
    }
}

/* Income highlighting */
.text-success {
    color: #28a745 !important;
}

.text-primary {
    color: #007bff !important;
}

.fs-5 {
    font-size: 1.25rem !important;
}

/* Bank details section styling */
.bg-info {
    background-color: #17a2b8 !important;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}
</style>
@endsection