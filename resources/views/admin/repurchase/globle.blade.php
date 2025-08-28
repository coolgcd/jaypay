@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container">
            <table width="100%" class="table table-bordered table-striped" style="font-size:11px;" border="1">
                <tr style="background:#FFD3A8!important;">
                    <td align="center" nowrap><strong>Sr. No.</strong></td>
                    <td width="3%" nowrap><strong>Rec. MemID</strong></td>
                    <td width="4%" nowrap><strong>Name</strong></td>
                    <td width="7%" nowrap>CTO</td>
                    <td width="10%" nowrap><strong>Total BV</strong></td>
                    <td width="10%" align="center" nowrap><strong>BV Unit</strong></td>
                    <td width="10%" align="center" nowrap><strong>Rate</strong></td>
                    <td width="10%" align="center" nowrap><strong>Tot. Amount</strong></td>
                </tr>
                @php
                    $SSn = 1; // Initialize serial number
                @endphp
                @foreach ($members as $Selmnfarr)
                    @php
                        $BvUnit = intval($Selmnfarr->selfpurchase / 1000);
                        $Amount = $BvUnit * $repurchase->global_unit_rate; // Assuming this value comes from the repurchase
                    @endphp
                    <tr>
                        <td width="3%" align="center" nowrap>{{ $SSn++ }}</td>
                        <td nowrap>{{ $Selmnfarr->memid }}</td>
                        <td nowrap>{{ App\Models\Member::getNameById($Selmnfarr->memid) }}</td>
                        <td nowrap>{{ $Selmnfarr->cto }}</td>
                        <td nowrap>{{ $Selmnfarr->selfpurchase }}</td>
                        <td align="center" nowrap>{{ $BvUnit }}</td>
                        <td align="center" nowrap>{{ $repurchase->global_unit_rate }}</td>
                        <td align="center" nowrap>{{ $Amount }}</td>
                    </tr>
                @endforeach
            </table>

        </div>
       </div>
@endsection
