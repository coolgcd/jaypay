@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">

        {{-- <div class="container"> --}}
            <h3>Manage Activation Orders</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('orders.update') }}" method="POST">
                @csrf
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>St/Dist Details</th>
                            <th>Member ID</th>
                            <th>Person Name</th>
                            <th>Mobile</th>
                            <th>BV</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->orderid }}</td>
                                <td>{{ $order->stokist_dist }}</td>
                                <td>{{ $order->memid }}</td>
                                <td>{{ $order->mem_name }}</td>
                                <td>{{ $order->mobile }}</td>
                                <td>{{ $order->tot_bv }}</td>
                                <td>{{ $order->tot_amt + $order->shipping_chrg }}</td>
                                {{-- <td>{{ $order->created_at->format('d-m-Y') }}</td> --}}
                                <td>
                                    <a href="{{ route('orders.cancel', $order->orderid) }}" class="btn btn-warning">Cancel</a>
                                    <button type="submit" name="chkDelete[]" value="{{ $order->orderid }}" class="btn btn-danger">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- <div class="pagination">
                    {{ $orders->links() }}
                </div> --}}
            </form>
        </div>
       </div>
@endsection
