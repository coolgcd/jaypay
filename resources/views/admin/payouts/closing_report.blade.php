@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="secondary-navigation">
            <form action="{{ url()->current() }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="sec-name">Manage Closing Report Income</div>
                <div class="sec-button">
                    <div class="actions">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="font-weight:bold;">Select Closing List:&nbsp;</td>
                                    <td>
                                        <select name="closdate" id="closdate" class="form-control">
                                            <option value="" selected>Select closing</option>
                                            @foreach($closingDates as $date)
                                                <option value="{{ $date->closing_date }}"
                                                    {{ Session::get('ClosSession') == $date->closing_date ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::createFromTimestamp($date->closing_date)->format('d-m-Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="submit" class="btn btn-danger" value="Search Member"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </form>
        </div>

        <div class="graphs">
            @if($closingData->isEmpty())
                <div>No closing data available!</div>
            @else
                <div class="xs">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr style="background:#FFD3A8!important;">
                                    <th>Sr. No.</th>
                                    <th>Rec. MemID</th>
                                    <th>Name</th>
                                    <th>Closing Date</th>
                                    <th>Reward</th>
                                    <th>Lev. Income</th>
                                    <th>Silver C. Income</th>
                                    <th>Upline Income</th>
                                    <th>Downline Income</th>
                                    <th>Repurchase Income</th>
                                    <th>Tot. Amount</th>
                                    <th>TDS 5%</th>
                                    <th>Admin 5%</th>
                                    <th>Net Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($closingData as $index => $data)
                                    @php
                                        $MTotAmt = $data->level_income + $data->silver_club_income + $data->upline_income +
                                            $data->downline_income + $data->repurchase_income + $data->recharge_income + $data->stoki_spon_income;
                                        $Tds = round($MTotAmt * 5 / 100);
                                        $Admin = round($MTotAmt * 5 / 100);
                                        $NetAmt = $MTotAmt - ($Tds + $Admin);
                                    @endphp
                                    <tr style="background-color:#0000FF; color:#FFFFFF; font-weight:bold;">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data->memid }}</td>
                                        <td>{{ getMemName($data->memid) }}</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($data->closing_date)->format('h:i d-m-Y') }}</td>
                                        <td>{{ $data->reward_income }}</td>
                                        <td>{{ $data->level_income }}</td>
                                        <td>{{ $data->silver_club_income }}</td>
                                        <td>{{ $data->upline_income }}</td>
                                        <td>{{ $data->downline_income }}</td>
                                        <td>{{ $data->repurchase_income }}</td>
                                        <td>{{ $MTotAmt }}</td>
                                        <td>{{ $Tds }}</td>
                                        <td>{{ $Admin }}</td>
                                        <td>{{ $NetAmt }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
       </div>
@endsection
