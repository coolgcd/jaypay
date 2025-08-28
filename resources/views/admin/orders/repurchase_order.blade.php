@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            <form action="{{ route('process.orders') }}" method="post">
                @csrf
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <h3>Manage Repurchase Orders</h3>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Order ID</th>
                                <th>St/Dist Details</th>
                                <th>Member ID</th>
                                <th>Person Name</th>
                                <th>Mobile</th>
                                <th>BV</th>
                                <th>Total Amount</th>
                                <th>Order Date</th>
                                <th>View Details</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $key => $order)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $order->orderid }}</td>
                                    <td>{{ $order->stokist_dist }}</td>
                                    <td>{{ $order->memid }}</td>
                                    <td>{{ $order->mem_name }}</td>
                                    <td>{{ $order->mobile }}</td>
                                    <td>{{ $order->tot_bv }}</td>
                                    <td>{{ $order->tot_amt + $order->shipping_chrg }}</td>
                                    <td>{{ date('d-m-Y', $order->order_date) }}</td>
                                    <td>
                                        <a href="">View</a> |
                                        <a href="javascript:void(0)" onclick="PrintInvoice('{{ base64_encode($order->orderid) }}')">Invoice</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('cancel.order', $order->orderid) }}">Cancel</a> |
                                        <a href="{{ route('delete.order', $order->orderid) }}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $orders->links() }} <!-- Pagination links -->
                </div>
            </form>
        </div>
       </div>
@endsection
