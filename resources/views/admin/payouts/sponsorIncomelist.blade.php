@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="block" id="block-tables">
            <div class="secondary-navigation">
                <div class="sec-name">Manage Stockist List</div>
                <div class="sec-button">
                    <div class="actions">
                        <div class="table-responsive">
                            <form method="post" action="{{ route('manage-stockist.search') }}">
                                @csrf
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="font-weight:bold;">Select Date</td>
                                        <td>
                                            <input name="fromdate" type="date" class="form-control" value="{{ request('fromdate') }}">
                                        </td>
                                        <td>
                                            <input name="todate" type="date" class="form-control" value="{{ request('todate') }}">
                                        </td>
                                        <td>
                                            <input name="Submit" type="submit" class="btn btn-danger" value="Search Record">
                                            <input name="hdnsearch" type="hidden" value="1">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            @if($stockistData)
            <div class="content">
                <div class="inner">
                    <div><h4>Report from date {{ request('fromdate') }} to {{ request('todate') }}</h4></div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SI.No.</th>
                                    <th>Sponsor ID</th>
                                    <th>Sponsor Name</th>
                                    <th>Stockist ID</th>
                                    <th>Stockist Name</th>
                                    <th>Total Amount</th>
                                    <th>Total 2%</th>
                                    <th>TDS</th>
                                    <th>Admin</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockistData as $index => $stockist)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stockist->sponsor_id }}</td>
                                    <td>{{ $stockist->sponsor_name }}</td>
                                    <td>{{ $stockist->stockist_id }}</td>
                                    <td>{{ $stockist->stockist_name }}</td>
                                    <td>{{ $stockist->turnover }}</td>
                                    <td>{{ $stockist->commission }}</td>
                                    <td>{{ $stockist->tds }}</td>
                                    <td>{{ $stockist->admin_fee }}</td>
                                    <td>{{ $stockist->final_amount }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
       </div>
@endsection
