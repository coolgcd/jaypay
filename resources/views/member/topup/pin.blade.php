@extends('member.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Topup Pin</h4>
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
                                    <th>Price</th>
                                    <th>Tpin</th>
                                    <th>GDate</th>
                                    <th>UserID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($availablePins->count() > 0)
                                @foreach($availablePins as $pin)
                                <tr>
                                    <td>₹{{ number_format($pin->pinamt) }}</td>
                                    <td>{{ $pin->pincode }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pin->created_at)->format('d-m-Y h:i A') }}</td>
                                    <td>{{ $pin->member_id }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#topupModal{{ $pin->id }}">
                                            Top Up
                                        </button>

                                        <!-- Bootstrap 5 Modal -->
                                        <div class="modal fade" id="topupModal{{ $pin->id }}" tabindex="-1" aria-labelledby="topupModalLabel{{ $pin->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="topupModalLabel{{ $pin->id }}">Use PIN: {{ $pin->pincode }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('member.topup.process') }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="pin_id" value="{{ $pin->id }}">

                                                            <!-- <div class="mb-3">
                                                                        <label for="new_member_id{{ $pin->id }}" class="form-label">New Member ID</label>
                                                                        <input type="text" name="new_member_id" id="new_member_id{{ $pin->id }}" class="form-control" placeholder="Enter Member ID to topup" required>
                                                                    </div> -->

                                                            <div class="mb-3">
                                                                <label for="new_member_id{{ $pin->id }}" class="form-label">New Member ID</label>
                                                                <input type="text" name="new_member_id" id="new_member_id{{ $pin->id }}" class="form-control member-id-input"
                                                                    data-name-field="member_name{{ $pin->id }}"
                                                                    placeholder="Enter Member ID to topup" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Member Name</label>
                                                                <input type="text" class="form-control" id="member_name{{ $pin->id }}" readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="password{{ $pin->id }}" class="form-label">Your Password</label>
                                                                <input type="password" name="password" id="password{{ $pin->id }}" class="form-control" placeholder="Enter your password" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Confirm Topup</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" class="text-center">No available PINs found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{ $availablePins->links() }}

                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.member-id-input').forEach(input => {
    input.addEventListener('keyup', function () {
        const memberId = this.value;
        const nameFieldId = this.dataset.nameField;
        const nameField = document.getElementById(nameFieldId);

        if (memberId.length > 0) {
            fetch(`/member/get-name/${memberId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        nameField.value = data.name;
                    } else {
                        nameField.value = 'Not found';
                    }
                })
                .catch(() => {
                    nameField.value = 'Error';
                });
        } else {
            nameField.value = '';
        }
    });
});
</script>

@endsection