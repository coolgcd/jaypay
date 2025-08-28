@extends('member.layout')

@section('title', 'Edit Profile')

@section('content')
<style>/* Card Style */
.card-section {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: box-shadow 0.3s ease;
}
.card-section:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.10);
}
.card-section h5 {
    font-size: 1.125rem;
    font-weight: 600;
    border-bottom: 2px solid #2196f3;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    color: #2196f3;
}
.list-group-item {
    background: #f1f8ff;
    border: none;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: background 0.2s ease;
}
.list-group-item:hover {
    background: #e0f2ff;
}
.list-group-item strong {
    display: inline-block;
    min-width: 120px;
    color: #333;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card-section">
            <h5>Edit Basic Information</h5>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('member.profile.update') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ $member->name }}" class="form-control" disabled>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender', $member->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $member->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $member->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $member->mobileno) }}" class="form-control @error('mobile') is-invalid @enderror" required>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" value="{{ $member->emailid }}" class="form-control" disabled>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $member->address) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $member->city) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" value="{{ old('country', $member->country) }}" class="form-control">
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('member.profile') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
