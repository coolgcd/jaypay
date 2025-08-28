@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Used PINs</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.NO.</th>
                                    <th>Member</th>
                                    <th>Pin Value</th>
                                    <th>Used By</th>
                                    <th>Create Date</th>
                                    <th>Used Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($usedPins->count() > 0)
                                    @foreach($usedPins as $index => $pin)
                                        <tr>
                                            <td>{{ $usedPins->firstItem() + $index }}</td>
                                            <td>
                                                {{ $pin->member_name }}<br>
                                                <small class="text-muted">({{ $pin->member_id }})</small>
                                            </td>
                                            <td>₹{{ number_format($pin->pinamt) }}</td>
                                            <td>
                                                @if($pin->used_by_name)
                                                    {{ $pin->used_by_name }}<br>
                                                    <small class="text-muted">({{ $pin->used_by_id }})</small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($pin->created_at)->format('d-m-Y h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pin->used_at)->format('h:i A d-m-Y') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No used PINs found</td>
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
@endsection
