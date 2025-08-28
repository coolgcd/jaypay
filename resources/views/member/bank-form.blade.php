@extends('member.layout') {{-- Adjust based on your layout --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Submit Bank Details</h4>
                    <p class="text-muted mb-0">Note: You can only submit bank details once. Please ensure all information is correct.</p>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('member.bank.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Account Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Account Information</h5>
                                
                                <div class="mb-3">
                                    <label for="accname" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('accname') is-invalid @enderror" 
                                           id="accname" name="accname" value="{{ old('accname') }}" required>
                                    @error('accname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="acctype" class="form-label">Account Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('acctype') is-invalid @enderror" id="acctype" name="acctype" required>
                                        <option value="">Select Account Type</option>
                                        <option value="Savings" {{ old('acctype') == 'Savings' ? 'selected' : '' }}>Savings</option>
                                        <option value="Current" {{ old('acctype') == 'Current' ? 'selected' : '' }}>Current</option>
                                        <option value="Salary" {{ old('acctype') == 'Salary' ? 'selected' : '' }}>Salary</option>
                                    </select>
                                    @error('acctype')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="acc_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('acc_number') is-invalid @enderror" 
                                           id="acc_number" name="acc_number" value="{{ old('acc_number') }}" required>
                                    @error('acc_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                           id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="branch" class="form-label">Branch <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('branch') is-invalid @enderror" 
                                           id="branch" name="branch" value="{{ old('branch') }}" required>
                                    @error('branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="ifsc_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ifsc_code') is-invalid @enderror" 
                                           id="ifsc_code" name="ifsc_code" value="{{ old('ifsc_code') }}" 
                                           placeholder="e.g., SBIN0001234" required>
                                    @error('ifsc_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Bank Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="micr" class="form-label">MICR Code</label>
                                    <input type="text" class="form-control @error('micr') is-invalid @enderror" 
                                           id="micr" name="micr" value="{{ old('micr') }}">
                                    @error('micr')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Personal & Payment Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Personal Information</h5>
                                
                                <div class="mb-3">
                                    <label for="pannumber" class="form-label">PAN Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pannumber') is-invalid @enderror" 
                                           id="pannumber" name="pannumber" value="{{ old('pannumber') }}" 
                                           placeholder="e.g., ABCDE1234F" required>
                                    @error('pannumber')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="aadhar_number" class="form-label">Aadhar Number</label>
                                    <input type="text" class="form-control @error('aadhar_number') is-invalid @enderror" 
                                           id="aadhar_number" name="aadhar_number" value="{{ old('aadhar_number') }}" 
                                           placeholder="12 digit number">
                                    @error('aadhar_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <h5 class="mb-3 mt-4">Digital Payment Information</h5>
                                
                                <div class="mb-3">
                                    <label for="googlepay" class="form-label">Google Pay</label>
                                    <input type="text" class="form-control @error('googlepay') is-invalid @enderror" 
                                           id="googlepay" name="googlepay" value="{{ old('googlepay') }}" 
                                           placeholder="Phone number or UPI ID">
                                    @error('googlepay')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phonepay" class="form-label">PhonePe</label>
                                    <input type="text" class="form-control @error('phonepay') is-invalid @enderror" 
                                           id="phonepay" name="phonepay" value="{{ old('phonepay') }}" 
                                           placeholder="Phone number or UPI ID">
                                    @error('phonepay')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-warning mt-4">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Important Note:</h6>
                                    <ul class="mb-0">
                                        <li>Double-check all information before submitting</li>
                                        <li>Bank details can only be submitted once</li>
                                        <li>Contact support if you need to make changes</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="confirm_details" required>
                                    <label class="form-check-label" for="confirm_details">
                                        I confirm that all the information provided above is correct and I understand that these details cannot be changed once submitted.
                                    </label>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Submit Bank Details
                                </button>
                                <a href="{{ route('member.dashboard') }}" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Format IFSC code to uppercase
    document.getElementById('ifsc_code').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
    
    // Format PAN number to uppercase
    document.getElementById('pannumber').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
    
    // Format Aadhar number (remove spaces and limit to 12 digits)
    document.getElementById('aadhar_number').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 12);
    });
</script>
@endsection