@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            @if (session('errorMsg'))
                <div class="myalert-unsus">
                    {{ session('errorMsg') }}
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
            @endif

            <div class="secondary-navigation">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="sec-name">Settlement Closing</div>
                    <div class="sec-button">
                        <div class="actions">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="font-weight:bold;">Select Closing List:&nbsp;</td>
                                        <td>
                                            <select name="closdate" id="closdate" class="form-control">
                                                <option value="" selected>Select closing</option>
                                                @foreach ($closingDates as $closingDate)
                                                    <option value="{{ $closingDate }}" {{ session('ClosSession') == $closingDate ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::createFromTimestamp($closingDate)->format('d-m-Y') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input name="Submit2" type="submit" class="btn btn-danger" value="Search Member" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>

            <div class="xs">
                <div class="table-responsive">
                    <input type="button" name="Confirm" id="Confirm" value="Print Report" class="btn btn-default" onclick="PrintReport()" />
                    <table class="table table-bordered table-striped">
                        <tr style="background:#FFD3A8!important;">
                            <th>Sr. No.</th>
                            <th>Rec. MemID</th>
                            <th>Name</th>
                            <th>Closing Date</th>
                            <th>Total</th>
                            <th>TDS 5%</th>
                            <th>Admin 5%</th>
                            <th>Net Amount</th>
                        </tr>

                        @php
                            $SSn = $records->firstItem();
                            $totRepurchase = $mtotPayout = $mtotTds = $mtotAdm = $mgrossAmt = 0;
                        @endphp

                        @foreach ($records as $record)
                            @php
                                $MTotAmt = $record->oldpayment;
                                $Tdsm = round($MTotAmt * 5 / 100);
                                $Admin = round($MTotAmt * 5 / 100);
                                $NEtAmt = $MTotAmt - ($Tdsm + $Admin);

                                $totRepurchase += $record->oldpayment;
                                $mtotPayout += $NEtAmt;
                                $mtotTds += $Tdsm;
                                $mtotAdm += $Admin;
                                $mgrossAmt += $MTotAmt;
                            @endphp
                            <tr>
                                <td style="background-color:#0000FF; color:#FFFFFF; font-weight:bold;">{{ $SSn++ }}</td>
                                <td style="background-color:#0000FF; color:#FFFFFF; font-weight:bold;">{{ $record->memid }}</td>
                                <td style="background-color:#0000FF; color:#FFFFFF; font-weight:bold;">{{ getMemName($record->memid) }}</td>
                                <td align="center">{{ \Carbon\Carbon::createFromTimestamp($record->closing_date)->format('h:i d-m-Y') }}</td>
                                <td align="center">{{ $record->oldpayment }}</td>
                                <td align="center">{{ $Tdsm }}</td>
                                <td align="center">{{ $Admin }}</td>
                                <td align="center">{{ $NEtAmt }}</td>
                            </tr>
                            <tr>
                                <td colspan="8" style="height:5px; background-color:#0000CC;"></td>
                            </tr>
                        @endforeach

                        <tr style="background:#FFD3A8!important;">
                            <td colspan="4"></td>
                            <td>{{ $totRepurchase }}</td>
                            <td>{{ $mtotTds }}</td>
                            <td>{{ $mtotAdm }}</td>
                            <td>{{ $mtotPayout }}</td>
                        </tr>

                        @if ($records->isEmpty())
                            <tr>
                                <td colspan="8" style="text-align:center; color:#FF0000;">
                                    No closing data!
                                </td>
                            </tr>
                        @endif
                    </table>

                    {{ $records->links() }}
                </div>
            </div>
        </div>
       </div>
@endsection
