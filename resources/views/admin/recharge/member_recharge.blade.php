@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            @if(session('errorMsg'))
                <div class="myalert-unsus">{{ session('errorMsg') }} <i class="fa fa-exclamation-triangle"></i></div>
            @endif

            <h3 class="blank1">Member Recharge History</h3>

            <div class="xs">
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered table-striped">
                        <thead>
                            <tr style="background:#FFD3A8!important;">
                                <th width="5%"><strong>Sr. No.</strong></th>
                                <th width="4%"><strong>Rec. MemID</strong></th>
                                <th width="5%"><strong>Name</strong></th>
                                <th width="13%"><strong>Recharge Type</strong></th>
                                <th width="14%"><strong>Recharge ID</strong></th>
                                <th width="13%"><strong>Company</strong></th>
                                <th width="14%"><strong>Rec Date</strong></th>
                                <th width="23%" align="center"><strong>Recharge Amount</strong></th>
                                <th width="23%" align="center"><strong>Cashback</strong></th>
                                <th width="11%" align="center"><strong>Global Cashback</strong></th>
                                <th width="12%" align="center"><strong>Status</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recharges as $index => $recharge)
                                @php
                                    $operatorMap = [
                                        88 => 'JIO',
                                        1 => 'Airtel',
                                        3 => 'Idea',
                                        2 => 'Vodafone',
                                        8 => 'BSNL'
                                    ];
                                    $operator = $operatorMap[$recharge->operator] ?? 'Unknown'; // Fix the spelling of 'operator'
                                    $bgColor = $recharge->rec_status == 2 ? '#ff0000' : '#006600';
                                    $txtColor = '#ffffff';
                                @endphp

                                <tr>
                                    <td nowrap style="background-color:{{ $bgColor }}; color:{{ $txtColor }};">{{ $index + 1 }}</td>
                                    <td nowrap>{{ $recharge->memid }}</td>
                                    <td nowrap>{{ \App\Models\Member::getNameById($recharge->memid) }}</td> <!-- Use the model method here -->
                                    <td nowrap>{{ $recharge->rech_type }}</td>
                                    <td nowrap>{{ $recharge->mobileno }}</td>
                                    <td nowrap>{{ $operator }}</td>
                                    <td nowrap>{{ date('d-m-Y', $recharge->add_date) }}</td>
                                    <td align="center" nowrap>{{ $recharge->amount }}</td>
                                    <td align="center" nowrap>{{ $recharge->self_comm }}</td>
                                    <td align="center" nowrap>{{ $recharge->global_comm }}</td> <!-- Use the correct property name -->
                                    <td align="center" nowrap style="background-color:{{ $bgColor }}; color:{{ $txtColor }};">{{ $recharge->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" style="text-align:center; color:#FF0000;">No Offline Order!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $recharges->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
