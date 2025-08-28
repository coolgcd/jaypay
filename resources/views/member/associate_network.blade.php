@extends('member.layout') {{-- Or your layout --}}


@section('title', 'Associates Network')

@section('content')
<style>/* Associates Network Styles */
.container {
    max-width: 1200px;
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
    border-bottom: 3px solid #3498db;
    position: relative;
}

.container h2::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(45deg, #3498db, #2980b9);
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

.table-bordered {
    border: none;
}

.table-hover tbody tr {
    transition: all 0.3s ease;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Table Header */
.table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.table thead th {
    background: transparent;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 18px 15px;
    border: none;
    text-align: center;
    position: relative;
}

.table thead th:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0;
    top: 25%;
    height: 50%;
    width: 1px;
    background: rgba(255, 255, 255, 0.2);
}

/* Table Body */
.table tbody td {
    padding: 15px;
    border: none;
    border-bottom: 1px solid #e9ecef;
    text-align: center;
    vertical-align: middle;
    color: #495057;
    font-size: 0.9rem;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

/* Specific Column Styling */
.table tbody td:first-child {
    font-weight: 600;
    color: #2c3e50;
    background: linear-gradient(45deg, #f8f9fa, #ffffff);
}

.table tbody td:nth-child(2) {
    font-weight: 500;
    color: #2980b9;
}

.table tbody td:nth-child(4) {
    font-style: italic;
    color: #7f8c8d;
}

/* Date Formatting */
.table tbody td:nth-child(5),
.table tbody td:nth-child(6) {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    background: #f8f9fa;
    border-radius: 4px;
    font-weight: 500;
}

/* No Data Message */
.container p {
    text-align: center;
    font-size: 1.1rem;
    color: #7f8c8d;
    background: #f8f9fa;
    padding: 40px 20px;
    border-radius: 8px;
    border-left: 4px solid #e74c3c;
    margin: 30px 0;
}

/* Pagination Styles */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 30px;
    gap: 5px;
}

.pagination .page-item .page-link {
    color: #667eea;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.pagination .page-item .page-link:hover {
    color: #fff;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.active .page-link {
    color: #fff;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-color: #667eea;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    cursor: not-allowed;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 10px;
    }
    
    .container h2 {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table {
        min-width: 800px;
    }
    
    .table thead th,
    .table tbody td {
        padding: 12px 8px;
        font-size: 0.8rem;
    }
    
    .container p {
        padding: 30px 15px;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .container h2 {
        font-size: 1.5rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 10px 6px;
        font-size: 0.75rem;
    }
}</style>
<div class="container">
    <h2>Associate Network</h2>

    @if($associates->count())
    <div class="table-container">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Member Name</th>
                <th>Sponsor ID</th>
                <th>Sponsor Name</th>
                <th>Join Date</th>
                <th>Activation Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($associates as $associate)
            <tr>
                <td>{{ $associate->show_mem_id }}</td>
                <td>{{ $associate->name }}</td>
                <td>{{ $associate->sponsorid }}</td>
              @php
    $sponsor = \App\Models\Member::where('show_mem_id', $associate->sponsorid)->first();
@endphp

<td>
    @if ($sponsor)
        {{ $sponsor->name }} ({{ $sponsor->show_mem_id }})
    @else
        N/A
    @endif
</td>
                <td>{{ date('d M Y', strtotime($associate->joindate)) }}</td>
                <td>{{ $associate->activate_date ? date('d M Y', $associate->activate_date) : 'N/A' }}

</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    {{ $associates->links() }}
    @else
    <p>No associates found in your network.</p>
    @endif
</div>
@endsection
