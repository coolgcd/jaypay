@extends('admin.layout')

@section('content')
<div class="container-fluid mt-4">
    <h4 class="mb-3">Matching Income Report</h4>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.income.matching') }}" class="row mb-4">
        <div class="col-md-3">
            <input type="text" name="show_mem_id" class="form-control" placeholder="Member ID" value="{{ request('show_mem_id') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ request('name') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Show Member ID</th>
                    <th>Name</th>
                    <th>Total Left</th>
                    <th>Total Right</th>
                    <th>Used Left</th>
                    <th>Used Right</th>
                    <th>Left Carry</th>
                    <th>Right Carry</th>
                    <th>Total Match</th>
                    <th>Pay Amount</th>
                    <th>Status</th>
                    <th>Confirm Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->show_mem_id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->totleft_amount }}</td>
                    <td>{{ $item->totright_amount }}</td>
                    <td>{{ $item->used_left }}</td>
                    <td>{{ $item->used_right }}</td>
                    <td>{{ $item->leftcarry }}</td>
                    <td>{{ $item->rightcarry }}</td>
                    <td>{{ $item->tot_matching }}</td>
                    <td>₹{{ number_format($item->payamt, 2) }}</td>
                    <td>
                        @if($item->status == 1)
                            <span class="badge bg-success">Confirmed</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($item->confirm_date)->format('d-m-Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="13">No matching income records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $data->appends(request()->query())->links() }}
    </div>
</div>
@endsection
