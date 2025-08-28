@extends('member.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Used Pin Details</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.NO.</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($usedPins->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Price</th>
                                                <th>Pin</th>
                                                <th>Generated Date</th>
                                                <th>Used By</th>
                                                <th>Used Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($usedPins as $index => $pin)
                                            <tr>
                                                <td>{{ $usedPins->firstItem() + $index }}</td>
                                                <td>₹{{ number_format($pin->pinamt) }}</td>
                                                <td>{{ $pin->pincode }}</td>
                                                <td>{{ \Carbon\Carbon::parse($pin->created_at)->format('d-m-Y h:i A') }}</td>
                                                <td>{{ $pin->used_by_name }} ({{ $pin->used_by_id }})</td>
                                                <td>{{ \Carbon\Carbon::parse($pin->used_at)->format('h:i A d-m-Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <tr>
                                    <td colspan="2" class="text-center">No used PINs found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{ $usedPins->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">
    {!! $usedPins->links() !!}
</div>
<style>
    .pin-details {
        line-height: 1.8;
    }
</style>
@endsection