@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Unused PINs</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.NO.</th>
                                    <th>Member</th>
                                    <th>Pin Value</th>
                                    <th>Create Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($unusedPins->count() > 0)
                                    @foreach($unusedPins as $index => $pin)
                                        <tr>
                                            <td>{{ $unusedPins->firstItem() + $index }}</td>
                                            <td>
                                                {{ $pin->member_name }}<br>
                                                <small class="text-muted">({{ $pin->member_id }})</small>
                                            </td>
                                            <td>₹{{ number_format($pin->pinamt) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pin->created_at)->format('d-m-Y h:i A') }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.topuppin.delete', $pin->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this PIN?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No unused PINs found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $unusedPins->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection