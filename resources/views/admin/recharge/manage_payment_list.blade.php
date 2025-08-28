@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
<div class="container">
    @if($msg)
        <div class="alert alert-success">
            {{ $msg }}
        </div>
    @endif

    <h2>Manage Payment List</h2>
    <form id="frmsubmit" method="post" action="{{ route('manage.payment') }}">
        @csrf
        <div class="form-group">
            <label for="memid">Member ID</label>
            <input type="number" class="form-control" name="memid" id="memid" required>
        </div>
        <div class="form-group">
            <label for="fundval">Amount</label>
            <input type="number" class="form-control" name="fundval" id="fundval" required>
        </div>
        <input type="hidden" name="hdntransfer" value="1">
        <button type="submit" class="btn btn-primary">Transfer Fund To Member Account</button>
    </form>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>SI.No.</th>
                <th>Memid</th>
                <th>Member Name</th>
                <th>Transfer Amount</th>
                <th>Transfer Date</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rechargeWallets as $index => $wallet)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $wallet->memid }}</td>
                    <td>{{ DB::table('member')->where('mem_id', $wallet->memid)->value('name') }}</td>
                    <td>{{ $wallet->creditamt }}</td>
                    <td>{{ date('d-m-Y', $wallet->add_date) }}</td>
                    <td>
                        <form action="{{ route('manage.payment') }}" method="post" style="display:inline;">
                            @csrf
                            <input type="hidden" name="MTransID" value="{{ $wallet->ewallid }}">
                            <input type="hidden" name="Morl" value="Del">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Data Available!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $rechargeWallets->links() }} <!-- For pagination links -->
</div>
</div>
@endsection
