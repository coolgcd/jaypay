{{-- Laravel Blade Template --}}
@extends('member.layout')
@section('content')
<div class="payments-container">
    <div class="table-header">
        <h2>Payment History</h2>
    </div>
    
    <div class="table-wrapper">
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Screenshot</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td data-label="Amount">₹{{ number_format($payment->package_amount, 2) }}</td>
                    <td data-label="Qty">{{ $payment->quantity }}</td>
                    <td data-label="Total">₹{{ number_format($payment->total_amount, 2) }}</td>
                    <td data-label="Status">
                        <span class="status-badge status-{{ strtolower($payment->status) }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td data-label="Remarks">{{ $payment->admin_remarks ?: 'No remarks' }}</td>
                    <td data-label="Screenshot">
                        <a href="{{ asset('storage/' . $payment->screenshot_path) }}" 
                           target="_blank" 
                           class="view-link">
                            <i class="icon-view"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($payments->isEmpty())
        <div class="empty-state">
            <p>No payment records found.</p>
        </div>
        @endif
    </div>
</div>

<style>
/* Payments Table Styles */
.payments-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.table-header {
    margin-bottom: 25px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 15px;
}

.table-header h2 {
    color: #2c3e50;
    font-size: 28px;
    font-weight: 600;
    margin: 0;
    text-align: center;
}

.table-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.payments-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
    font-size: 14px;
}

.payments-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.payments-table thead th {
    padding: 18px 15px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 12px;
    border: none;
}

.payments-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.payments-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.payments-table tbody tr:last-child {
    border-bottom: none;
}

.payments-table td {
    padding: 16px 15px;
    vertical-align: middle;
    color: #495057;
    font-weight: 500;
}

/* Status Badge Styles */
.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-approved, .status-completed {
    background-color: #d4edda;
    color: #155724;
}

.status-rejected, .status-failed {
    background-color: #f8d7da;
    color: #721c24;
}

.status-processing {
    background-color: #d1ecf1;
    color: #0c5460;
}

/* View Link Styles */
.view-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.3s ease;
    border: 1px solid #007bff;
    background-color: transparent;
}

.view-link:hover {
    background-color: #007bff;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
}

.icon-view::before {
    content: "👁";
    margin-right: 3px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state p {
    font-size: 16px;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .payments-container {
        padding: 15px;
    }
    
    .table-header h2 {
        font-size: 24px;
    }
    
    .table-wrapper {
        border-radius: 8px;
    }
    
    .payments-table {
        font-size: 13px;
    }
    
    .payments-table thead {
        display: none;
    }
    
    .payments-table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .payments-table tbody tr:hover {
        transform: none;
    }
    
    .payments-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        border-bottom: 1px solid #f1f3f4;
        text-align: right;
    }
    
    .payments-table td:last-child {
        border-bottom: none;
    }
    
    .payments-table td::before {
        content: attr(data-label) ":";
        font-weight: 600;
        color: #495057;
        flex: 1;
        text-align: left;
    }
    
    .view-link {
        padding: 6px 10px;
        font-size: 12px;
    }
    
    .status-badge {
        font-size: 10px;
        padding: 4px 8px;
    }
}

@media (max-width: 480px) {
    .payments-container {
        padding: 10px;
    }
    
    .table-header {
        margin-bottom: 20px;
    }
    
    .payments-table td {
        padding: 10px 12px;
        font-size: 13px;
    }
}

/* Print Styles */
@media print {
    .payments-container {
        max-width: none;
        margin: 0;
        padding: 0;
    }
    
    .table-wrapper {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .payments-table thead {
        background: #f8f9fa !important;
        color: #000 !important;
    }
    
    .payments-table tbody tr:hover {
        background-color: transparent !important;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .view-link {
        color: #000 !important;
        border: 1px solid #000 !important;
    }
}
</style>
@endsection