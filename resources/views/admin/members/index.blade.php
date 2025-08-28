@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0">{{ $title }}</h2>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- Records Info -->
                    @isset($members->total)
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <p class="text-muted mb-0">
                                Showing {{ $members->firstItem() ?? 0 }} to {{ $members->lastItem() ?? 0 }} 
                                of {{ $members->total() }} results
                            </p>
                        </div>
                        <div class="col-sm-6 text-end">
                            <small class="text-muted">Page {{ $members->currentPage() }} of {{ $members->lastPage() }}</small>
                        </div>
                    </div>
                    @else
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <p class="text-muted mb-0">
                                Showing {{ $members->count() }} records
                            </p>
                        </div>
                    </div>
                    @endisset
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Member ID</th>
                                    <th scope="col">Name</th>
                                    <th>Join Date</th>
                                    
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $index => $member)
                                    <tr>
                                        <td>{{ $loop->iteration + (isset($members->currentPage) ? ($members->currentPage() - 1) * $members->perPage() : 0) }}</td>
                                        <td>{{ $member->show_mem_id }}</td>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($member->joindate)->format('d M Y') }}</td>

                                        
                                        <td>
                                            @if($member->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <form method="POST" action="{{ route('admin.members.toggleStatus', $member->show_mem_id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" 
                                                        onclick="return confirm('Are you sure you want to {{ $member->status ? 'deactivate' : 'activate' }} this member?')">
                                                    {{ $member->status ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </td>
                                        
                                        <td>
                                            <a href="{{ route('admin.member.view', $member->show_mem_id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>

                                            
    <a href="{{ route('admin.loginAsMember', $member->show_mem_id) }}" target="_blank" class="btn btn-sm btn-dark">
        <i class="fas fa-sign-in-alt"></i> Login
    </a>

        
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No members found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
              {{ $members->links('pagination::bootstrap-5') }}

                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional custom styles for members table */
.table th {
    white-space: nowrap;
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.btn-group-sm > .btn, .btn-sm {
    min-width: 80px;
}

/* Pagination custom styles */
.pagination {
    margin-bottom: 0;
}


.pagination .page-link {
    border: 1px solid #dee2e6;
    color: #6c757d;
    padding: 0.5rem 0.75rem;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #0056b3;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
}

.pagination-info {
    display: flex;
    align-items: center;
    height: 100%;
}

/* Serial number column */
.table th:first-child,
.table td:first-child {
    width: 60px;
    text-align: center;
}

/* Responsive table improvements */
@media (max-width: 768px) {
    .table-responsive table {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        min-width: 60px;
    }
    
    .pagination .page-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .pagination-info {
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .pagination {
        justify-content: center !important;
    }
}
</style>
@endsection