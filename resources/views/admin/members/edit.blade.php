@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Member - {{ $member->show_mem_id }}
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.member.update', $member->show_mem_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Member Basic Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user"></i> Basic Information
                                </h5>
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $member->name) }}" required>
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="father_name" class="form-label fw-bold">Father's Name</label>
                                <input type="text" class="form-control" id="father_name" name="father_name" 
                                       value="{{ old('father_name', $member->father_name) }}">
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="gender" class="form-label fw-bold">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $member->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $member->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $member->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="dob" class="form-label fw-bold">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" 
                                       value="{{ old('dob', $member->dob) }}">
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="emailid" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="emailid" name="emailid" 
                                       value="{{ old('emailid', $member->emailid) }}" required>
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="mobileno" class="form-label fw-bold">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mobileno" name="mobileno" 
                                       value="{{ old('mobileno', $member->mobileno) }}" required>
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="pannumber" class="form-label fw-bold">PAN Number</label>
                                <input type="text" class="form-control" id="pannumber" name="pannumber" 
                                       value="{{ old('pannumber', $member->pannumber) }}" maxlength="10" style="text-transform: uppercase;">
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" {{ old('status', $member->status) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $member->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marker-alt"></i> Address Information
                                </h5>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label fw-bold">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $member->address) }}</textarea>
                            </div>
                            
                            <div class="col-lg-4 col-md-12 mb-3">
                                <label for="city" class="form-label fw-bold">City</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="{{ old('city', $member->city) }}">
                            </div>
                            
                            <div class="col-lg-4 col-md-12 mb-3">
                                <label for="state" class="form-label fw-bold">State</label>
                                <input type="text" class="form-control" id="state" name="state" 
                                       value="{{ old('state', $member->state) }}">
                            </div>
                            
                            <div class="col-lg-4 col-md-12 mb-3">
                                <label for="pincode" class="form-label fw-bold">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode" 
                                       value="{{ old('pincode', $member->pincode) }}" maxlength="6">
                            </div>
                        </div>

                        <!-- Bank Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-university"></i> Bank Details
                                </h5>
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="accname" class="form-label fw-bold">Account Holder Name</label>
                                <input type="text" class="form-control" id="accname" name="accname" 
                                       value="{{ old('accname', $bankDetails->accname ?? '') }}">
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="acctype" class="form-label fw-bold">Account Type</label>
                                <select class="form-select" id="acctype" name="acctype">
                                    <option value="">Select Account Type</option>
                                    <option value="Savings" {{ old('acctype', $bankDetails->acctype ?? '') == 'Savings' ? 'selected' : '' }}>Savings</option>
                                    <option value="Current" {{ old('acctype', $bankDetails->acctype ?? '') == 'Current' ? 'selected' : '' }}>Current</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="acc_number" class="form-label fw-bold">Account Number</label>
                                <input type="text" class="form-control" id="acc_number" name="acc_number" 
                                       value="{{ old('acc_number', $bankDetails->acc_number ?? '') }}">
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="bank_name" class="form-label fw-bold">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                       value="{{ old('bank_name', $bankDetails->bank_name ?? '') }}">
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="branch" class="form-label fw-bold">Branch</label>
                                <input type="text" class="form-control" id="branch" name="branch" 
                                       value="{{ old('branch', $bankDetails->branch ?? '') }}">
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="ifsc_code" class="form-label fw-bold">IFSC Code</label>
                                <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" 
                                       value="{{ old('ifsc_code', $bankDetails->ifsc_code ?? '') }}" maxlength="11" style="text-transform: uppercase;">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="bank_address" class="form-label fw-bold">Bank Address</label>
                                <textarea class="form-control" id="bank_address" name="bank_address" rows="3">{{ old('bank_address', $bankDetails->address ?? '') }}</textarea>
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="micr" class="form-label fw-bold">MICR Code</label>
                                <input type="text" class="form-control" id="micr" name="micr" 
                                       value="{{ old('micr', $bankDetails->micr ?? '') }}" maxlength="9">
                            </div>
                            
                            <div class="col-lg-6 col-md-12 mb-3">
                                <label for="bank_pannumber" class="form-label fw-bold">PAN Number</label>
                                <input type="text" class="form-control" id="bank_pannumber" name="bank_pannumber" 
                                       value="{{ old('bank_pannumber', $bankDetails->pannumber ?? '') }}" maxlength="10" style="text-transform: uppercase;">
                            </div>
                            
                            <div class="col-lg-4 col-md-12 mb-3">
                                <label for="aadhar_number" class="form-label fw-bold">Aadhar Number</label>
                                <input type="text" class="form-control" id="aadhar_number" name="aadhar_number" 
                                       value="{{ old('aadhar_number', $bankDetails->aadhar_number ?? '') }}" maxlength="12">
                            </div>
                            
                            <div class="col-lg-4 col-md-12 mb-3">
                                <label for="googlepay" class="form-label fw-bold">Google Pay</label>
                                <input type="text" class="form-control" id="googlepay" name="googlepay" 
                                       value="{{ old('googlepay', $bankDetails->googlepay ?? '') }}">
                            </div>
                            
                            <div class="col-lg-4 col-md-12 mb-3">
                                <label for="phonepay" class="form-label fw-bold">Phone Pay</label>
                                <input type="text" class="form-control" id="phonepay" name="phonepay" 
                                       value="{{ old('phonepay', $bankDetails->phonepay ?? '') }}">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.member.view', $member->show_mem_id) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Member
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for edit form */
.form-label {
    color: #495057;
    font-weight: 500;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.text-danger {
    color: #dc3545 !important;
}

.border-bottom {
    border-bottom: 2px solid #dee2e6 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

/* Input styling */
input[type="text"]:read-only,
input[type="email"]:read-only {
    background-color: #f8f9fa;
}

/* Alert styles */
.alert {
    border-radius: 0.375rem;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert ul {
    padding-left: 1rem;
}
</style>

<script>
// Auto-uppercase PAN and IFSC fields
document.getElementById('pannumber').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});

document.getElementById('bank_pannumber').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});

document.getElementById('ifsc_code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});

// Validate PAN format
function validatePAN(pan) {
    const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
    return panPattern.test(pan);
}

// Validate IFSC format
function validateIFSC(ifsc) {
    const ifscPattern = /^[A-Z]{4}0[A-Z0-9]{6}$/;
    return ifscPattern.test(ifsc);
}

// Form validation on submit
document.querySelector('form').addEventListener('submit', function(e) {
    const panNumber = document.getElementById('pannumber').value;
    const bankPanNumber = document.getElementById('bank_pannumber').value;
    const ifscCode = document.getElementById('ifsc_code').value;
    
    // Validate PAN numbers if provided
    if (panNumber && !validatePAN(panNumber)) {
        alert('Please enter a valid PAN number format (e.g., ABCDE1234F)');
        e.preventDefault();
        return;
    }
    
    if (bankPanNumber && !validatePAN(bankPanNumber)) {
        alert('Please enter a valid Bank PAN number format (e.g., ABCDE1234F)');
        e.preventDefault();
        return;
    }
    
    // Validate IFSC code if provided
    if (ifscCode && !validateIFSC(ifscCode)) {
        alert('Please enter a valid IFSC code format (e.g., ABCD0123456)');
        e.preventDefault();
        return;
    }
});
</script>
@endsection