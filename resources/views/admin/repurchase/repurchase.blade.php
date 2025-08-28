@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="block" id="block-tables">
            <div class="secondary-navigation">
                <div class="sec-name">Repurchase Master</div>
                <div class="sec-button">
                    <div class="actions">
                        <!-- Add New Record Button -->
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="content">
                <div class="inner">
                    <div class="flash">
                        @if(session('errorMsg'))
                            <div class="message notice">
                                <p>{{ session('errorMsg') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table width="100%" class="table table-bordered">
                            <tr>
                                <th>SI.No.</th>
                                <th>Closing Date</th>
                                <th>Total Joining Amount</th>
                                <th>Total Turnover Repurchase BV</th>
                                <th>Global (10%)</th>
                                <th>Active (5%)</th>
                                <th>Leader (15%)</th>
                            </tr>

                            @if($repurchases->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center text-danger">No Data Available!</td>
                                </tr>
                            @else
                                @foreach($repurchases as $index => $repurchase)
                                    <tr>
                                        <td align="center">{{ $repurchases->firstItem() + $index }}</td>
                                        <td align="center">{{ date('d-m-Y', strtotime($repurchase->closing_date)) }}</td>
                                        <td align="center">{{ $repurchase->totjoingamt }}</td>
                                        <td align="center">{{ $repurchase->totcompanybv }}</td>
                                        <td align="center">{{ $repurchase->globalamt }}</td>
                                        <td align="center">{{ $repurchase->active_fund }}</td>
                                        <td align="center">{{ $repurchase->leadershipb }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>

                    <div class="actions-bar">
                        {{ $repurchases->links() }}
                    </div>
                </div>
            </div>
        </div>
       </div>
@endsection
