@extends('admin.layout')

@section('content')
<div class="container">
    <h4>Daily Income</h4>

    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col">
                <input type="text" name="show_mem_id" class="form-control" placeholder="Show Member ID" value="{{ request('show_mem_id') }}">
            </div>
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ request('name') }}">
            </div>
            <div class="col">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col">
                <button class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    @if($records->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Show Mem ID</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->show_mem_id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>₹{{ number_format($row->amount, 2) }}</td>
                    <td>{{ date('d M Y', $row->date) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $records->withQueryString()->links() }}
        </div>
    @else
        <div class="alert alert-warning">No daily income records found.</div>
    @endif
</div>
@endsection
