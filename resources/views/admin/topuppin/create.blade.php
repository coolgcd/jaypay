@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Generate TPIN</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        @if(session('generated_pins'))
                        <div class="mt-3">
                            <h5>Generated PINs:</h5>
                            @foreach(session('generated_pins') as $pin)
                            <div class="badge badge-primary mr-2 mb-2">{{ $pin }}</div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.topuppin.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pin Type</label>
                                    <select name="pintype" class="form-control" required>
    <option value="">Select Pin Type</option>
    @foreach($pinTypes as $type)
    <option value="{{ $type->id }}" 
        {{ (old('pintype') ?? $prefill['pin_type'] ?? '') == $type->pv_amount ? 'selected' : '' }}>
        {{ $type->pintype }} - ₹{{ $type->pv_amount }}
    </option>
    @endforeach
</select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Number of PINs (1-10)</label>
<input type="number" name="pin_count" class="form-control" min="1" max="10" required value="{{ old('pin_count', $prefill['quantity'] ?? '') }}">                                </div>
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <label>Member ID</label>
                            <input type="text" name="member_id" class="form-control" placeholder="Enter Member ID" required>
                        </div> -->
                        <div class="form-group">
                            <label>Member ID</label>
<input type="text" id="member_id_input" name="member_id" class="form-control" placeholder="Enter Member ID" required value="{{ old('member_id', $prefill['member_id'] ?? '') }}">                        </div>

                        <!-- <div class="form-group">
                            <label>Member Name</label>

<input type="text" id="member_name_display" class="form-control" readonly
    value="{{ $prefill['member_name'] ?? '' }}">                        </div> -->

<div class="form-group">
                            

                        <button type="submit" class="btn btn-primary">Generate PINs</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection