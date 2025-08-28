@extends('member.layout')

@section('title', 'My Profile')

@section('content')

<style>/* Core Card Style */
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

/* Section Headers */
.card-section h5 {
    font-size: 1.125rem;
    font-weight: 600;
    border-bottom: 2px solid #2196f3; /* Primary blue underline */
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    color: #2196f3;
}

/* List Group Items */
.list-group-item {
    background: #f1f8ff; /* Light blue background */
    border: none;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: background 0.2s ease;
}

.list-group-item:hover {
    background: #e0f2ff; /* Slightly darker on hover */
}

/* Strong labels */
.list-group-item strong {
    display: inline-block;
    min-width: 120px;
    color: #333;
}

/* No bank details text */
.text-danger {
    font-weight: 500;
}
</style>
<div class="row">
    <!-- Member Basic Info -->
    <div class="col-md-6">
        <div class="card-section">
            <h5>Basic Information</h5>
            <ul class="list-group">
                <li class="list-group-item"><strong>ID:</strong> {{ $member->show_mem_id }}</li>
                <li class="list-group-item"><strong>Name:</strong> {{ $member->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $member->emailid }}</li>
                <li class="list-group-item"><strong>Mobile:</strong> {{ $member->mobileno }}</li>
                <li class="list-group-item"><strong>Join Date:</strong> {{ $member->joindate }}</li>
                <li class="list-group-item"><strong>Activated On:</strong> {{ $member->activate_date }}</li>
                <li class="list-group-item"><strong>Sponsor:</strong> {{ $member->sponsorid }}</li>
            </ul>
        </div>
    </div>

    <!-- Bank Details -->
    <div class="col-md-6">
        <div class="card-section">
            <h5>Bank Information</h5>
            @if($member->member_bank_details)
                <ul class="list-group">
                    <li class="list-group-item"><strong>Account Name:</strong> {{ $member->member_bank_details->accname }}</li>
                    <li class="list-group-item"><strong>Bank:</strong> {{ $member->member_bank_details->bank_name }}</li>
                    <li class="list-group-item"><strong>Branch:</strong> {{ $member->member_bank_details->branch }}</li>
                    <li class="list-group-item"><strong>Account Type:</strong> {{ $member->member_bank_details->acctype }}</li>
                    <li class="list-group-item"><strong>IFSC:</strong> {{ $member->member_bank_details->ifsc_code }}</li>
                    <li class="list-group-item"><strong>PAN:</strong> {{ $member->member_bank_details->pannumber }}</li>
                    <li class="list-group-item"><strong>Aadhaar:</strong> {{ $member->member_bank_details->aadhar_number }}</li>
                </ul>
            @else
                <p class="text-danger mb-0">No bank details found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
