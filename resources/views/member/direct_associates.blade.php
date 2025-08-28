@extends('member.layout') {{-- or your layout --}}



@section('title', 'Direct Associates')

@section('content')
<style>/* Direct Associates Styles */
.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Page Header */
.container h2 {
    color: #2c3e50;
    font-size: 2.2rem;
    font-weight: 600;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 3px solid #27ae60;
    position: relative;
}

.container h2::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(45deg, #27ae60, #2ecc71);
    border-radius: 2px;
}

/* Table Container */
.table-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

/* Table Styles */
.table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
    background-color: transparent;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: linear-gradient(45deg, #f8f9fa, #ffffff);
}

.table-striped tbody tr:nth-of-type(even) {
    background: #ffffff;
}

.table tbody tr {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.table tbody tr:hover {
    background: linear-gradient(45deg, #e8f5e8, #f4f9f4) !important;
    transform: translateX(3px);
    border-left: 4px solid #27ae60;
    box-shadow: 0 2px 12px rgba(39, 174, 96, 0.15);
}

/* Table Header */
.table thead {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
}

.table thead th {
    background: transparent;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 20px 18px;
    border: none;
    text-align: center;
    position: relative;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.table thead th:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0;
    top: 25%;
    height: 50%;
    width: 1px;
    background: rgba(255, 255, 255, 0.3);
}

/* Table Body */
.table tbody td {
    padding: 18px;
    border: none;
    border-bottom: 1px solid #e9ecef;
    text-align: center;
    vertical-align: middle;
    color: #495057;
    font-size: 0.95rem;
    font-weight: 500;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

/* Member ID Column */
.table tbody td:first-child {
    font-weight: 700;
    color: #27ae60;
    background: rgba(39, 174, 96, 0.05);
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
}

/* Name Column */
.table tbody td:nth-child(2) {
    font-weight: 600;
    color: #2c3e50;
    text-align: left;
    padding-left: 25px;
}

/* Date Columns */
.table tbody td:nth-child(3),
.table tbody td:nth-child(4) {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    background: rgba(52, 152, 219, 0.05);
    border-radius: 4px;
    font-weight: 600;
    color: #2980b9;
}

/* N/A Styling */
.table tbody td:contains('N/A') {
    color: #e74c3c;
    font-style: italic;
    font-weight: 500;
}

/* Alternative N/A styling using attribute selector */
.table tbody td[data-status="inactive"] {
    color: #e74c3c;
    font-style: italic;
    font-weight: 500;
}

/* No Data Message */
.container p {
    text-align: center;
    font-size: 1.2rem;
    color: #7f8c8d;
    background: linear-gradient(45deg, #f8f9fa, #ffffff);
    padding: 50px 30px;
    border-radius: 12px;
    border-left: 5px solid #27ae60;
    margin: 30px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.container p::before {
    content: '👥';
    display: block;
    font-size: 2rem;
    margin-bottom: 15px;
}

/* Pagination Styles */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 30px;
    gap: 8px;
    flex-wrap: wrap;
}

.pagination .page-item .page-link {
    color: #27ae60;
    background-color: #fff;
    border: 2px solid #e9ecef;
    padding: 12px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.9rem;
}

.pagination .page-item .page-link:hover {
    color: #fff;
    background: linear-gradient(45deg, #27ae60, #2ecc71);
    border-color: #27ae60;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(39, 174, 96, 0.3);
}

.pagination .page-item.active .page-link {
    color: #fff;
    background: linear-gradient(45deg, #27ae60, #2ecc71);
    border-color: #27ae60;
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Summary Stats (if you want to add) */
.stats-summary {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.stat-card {
    background: linear-gradient(45deg, #27ae60, #2ecc71);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    flex: 1;
    min-width: 150px;
    box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);
}

.stat-card h3 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
}

.stat-card p {
    margin: 5px 0 0 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    .container h2 {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0 -15px;
        border-radius: 0;
    }
    
    .table {
        min-width: 600px;
    }
    
    .table thead th,
    .table tbody td {
        padding: 12px 10px;
        font-size: 0.85rem;
    }
    
    .table tbody td:nth-child(2) {
        text-align: center;
        padding-left: 10px;
    }
    
    .container p {
        padding: 40px 20px;
        font-size: 1rem;
        margin: 20px 0;
    }
    
    .stats-summary {
        flex-direction: column;
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .container h2 {
        font-size: 1.5rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 10px 8px;
        font-size: 0.8rem;
    }
    
    .pagination .page-item .page-link {
        padding: 10px 12px;
        font-size: 0.8rem;
    }
}</style>
<div class="container">
    <h2>Direct Associates</h2>

    @if($directAssociates->count())
    <div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Mem ID</th>
                <th>Name</th>
                <th>Join Date</th>
                <th>Activate Date</th>
                <!-- <th>Email</th>
                <th>Mobile</th> -->
                <!-- <th>Sponsor ID</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($directAssociates as $associate)
            <tr>
                <td>{{ $associate->show_mem_id }}</td>
                <td>{{ $associate->name }}</td>
                <td>{{ date('d M Y', strtotime($associate->joindate)) }}</td>
                <td>{{ $associate->activate_date ? date('d M Y', $associate->activate_date) : 'N/A' }}
</td>
                <!-- <td>{{ $associate->emailid }}</td>
                <td>{{ $associate->mobileno }}</td> -->
                <!-- <td>{{ $associate->sponsorid }}</td> -->
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    {{ $directAssociates->links() }}
    @else
    <p>No direct associates found.</p>
    @endif
</div>
@endsection
