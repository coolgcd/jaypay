@extends('member.layout')

@section('content')
<style>/* Table Container */
.table-responsive {
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
}

/* Table Base */
.table {
    margin-bottom: 0;
    background: #ffffff;
}

/* Table Head */
.table thead {
    background: #2196f3;
    color: #fff;
}
.table thead th {
    font-weight: 600;
    font-size: 0.95rem;
    border-bottom: none;
}

/* Table Rows */
.table tbody tr {
    transition: background 0.2s ease;
}
.table tbody tr:hover {
    background: #f1f8ff;
}

/* Table Cells */
.table td {
    vertical-align: middle;
    font-size: 0.93rem;
    border-color: #e0e0e0;
}

/* Badges inside table */
.badge {
    font-size: 0.85rem;
    padding: 0.4em 0.6em;
    border-radius: 0.35rem;
}

/* Active/Inactive Badges */
.badge-success {
    background-color: #4caf50;
    color: #fff;
}
.badge-secondary {
    background-color: #9e9e9e;
    color: #fff;
}

/* Position badge */
.badge-primary {
    background-color: #2196f3;
    color: #fff;
}

/* Package badge */
.bg-primary {
    background-color: #2196f3 !important;
    color: #fff !important;
}

/* Table Footer Note */
.table-footer-note {
    padding: 0.75rem 1rem;
    border-top: 1px solid #e0e0e0;
    background: #f9f9f9;
    border-radius: 0 0 0.5rem 0.5rem;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card-section">
            <h5>My Left Network</h5>

            @if(empty($result))
                <div class="alert alert-info mb-0">
                    <p>No left network members found.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Direct Children</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Join Date</th>
                                <th>Package</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result as $member)
                                <tr>
                                    <td>{{ $member['memid'] }}</td>
                                    <td>{{ $member['name'] }}</td>
                                    <td>{{ $member['down_to'] }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ ucfirst($member['position']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $member['status'] == 'Active' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $member['status'] }}
                                        </span>
                                    </td>
                                    <td>{{ $member['joindate'] }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            ₹{{ number_format($member['package'], 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer-note">
                    <strong>Total Left Network Members:</strong> {{ count($result) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
