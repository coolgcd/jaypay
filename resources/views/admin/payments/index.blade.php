@extends('admin.layout')
@section('content')
<style>
    .payments-container {
        padding: 20px;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .payments-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .payments-table th {
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #5a6c8a;
    }

    .payments-table td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        color: #495057;
        vertical-align: middle;
    }

    .payments-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .payments-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .view-link {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 4px;
        transition: all 0.2s ease;
        border: 1px solid #dee2e6;
        display: inline-block;
    }

    .view-link:hover {
        background-color: #007bff;
        color: white;
        text-decoration: none;
    }

    .action-form {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 180px;
    }

    .action-form select,
    .action-form input[type="text"] {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 13px;
        background-color: white;
        color: #495057;
    }

    .action-form select:focus,
    .action-form input[type="text"]:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .update-btn {
        padding: 8px 16px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .update-btn:hover {
        opacity: 0.9;
    }

    .table-header {
        margin-bottom: 20px;
    }

    .table-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
    }

    .status-locked {
        text-align: center;
        padding: 10px;
        background-color: #e9ecef;
        border-radius: 4px;
    }

    .status-locked .locked-message {
        font-weight: 600;
        color: #28a745;
    }

</style>
     @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        @if(session('generated_pins'))
                        <div class="mt-3">
                            <h5>Generated PINs:</h5>
                            @foreach(session('generated_pins') as $pin)
                            <div class="badge badge-primary mr-2 mb-2">{{ $pin }}</div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
<div class="payments-container">
    <div class="table-header">
        <h1 class="table-title">Payment Management</h1>
        <p class="text-muted">Review and manage member payment submissions.</p>
    </div>

    <div class="table-responsive">
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Amount</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Screenshot</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td><strong>{{ $payment->member_id }}</strong></td>
                    <td>₹{{ number_format($payment->package_amount, 2) }}</td>
                    <td>{{ $payment->quantity }}</td>
                    <td>₹{{ number_format($payment->total_amount, 2) }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($payment->status) }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ asset('storage/' . $payment->screenshot_path) }}" target="_blank" class="view-link">
                            View Screenshot
                        </a>
                    </td>
                    <td>
                        {{-- CORRECTED LOGIC STARTS HERE --}}
                        @if($payment->status == 'approved')
                            <div class="status-locked">
                                <span class="locked-message">✓ Approved</span>
                                <small class="d-block text-muted mt-1">Status is locked</small>
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.payments.update', $payment->id) }}" class="action-form">
                                @csrf
                                <select name="status">
                                    @if($payment->status == 'pending')
                                        <option value="pending" selected>Pending</option>
                                        <option value="approved">Approve</option>
                                        <option value="rejected">Reject</option>
                                    @elseif($payment->status == 'rejected')
                                        <option value="rejected" selected>Rejected</option>
                                        <option value="approved">Approve</option>
                                        <option value="pending">Set as Pending</option>
                                    @endif
                                </select>
                                <input type="text" name="admin_remarks" placeholder="Add remarks (optional)..." value="{{ $payment->admin_remarks ?? '' }}">
                                <button type="submit" class="update-btn">Update Status</button>
                            </form>
                        @endif
                        {{-- CORRECTED LOGIC ENDS HERE --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
