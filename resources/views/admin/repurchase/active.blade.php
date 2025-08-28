@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <table width="100%" class="table table-bordered table-striped" style="font-size:11px;" border="1">
            <tr style="background:#FFD3A8!important;">
                <td align="center" nowrap><strong>Sr. No.</strong></td>
                <td width="3%" nowrap><strong>Rec. MemID</strong></td>
                <td width="2%" nowrap><strong>Name</strong></td>
                <td width="2%" nowrap><strong>Left Bv</strong></td>
                <td width="4%" nowrap><strong>Right BV</strong></td>
                <td width="2%" nowrap><strong>LeftUnit</strong></td>
                <td width="1%" nowrap><strong>Right Unit</strong></td>
                <td width="1%" nowrap><strong>Match Unit</strong></td>
                <td width="7%" nowrap><strong>Total Repurchase BV 5%</strong></td>
                <td width="5%" nowrap><strong>Total Joining 5%</strong></td>
                <td width="5%" nowrap><strong>Total</strong></td>
                <td width="10%" nowrap><strong>Comp. Unit</strong></td>
                <td width="10%" align="center" nowrap><strong>Rate</strong></td>
                <td width="10%" align="center" nowrap><strong>Tot. Amount</strong></td>
            </tr>
            @php
                $SSn = 1; // Initialize serial number
                $TotMbc = round($repurchase->totcompanybv * 0.05);
                $TotMJoin = round($repurchase->totjoingamt * 0.05);
                $MunitRate = round(($TotMbc + $TotMJoin) / $repurchase->active_fun_unit);
            @endphp
            @foreach ($members as $member)
                @php
                    $Amount = $MunitRate * $member->matchbv;
                    $Gfamount = $TotMbc + $TotMJoin;
                @endphp
                <tr>
                    <td width="3%" align="center" nowrap>{{ $SSn++ }}</td>
                    <td nowrap>{{ $member->mem_id }}</td>
                    <td nowrap>{{ App\Models\Member::getNameById($member->mem_id) }}</td>
                    <td nowrap>{{ $member->leftbv }}</td>
                    <td nowrap>{{ $member->rightbv }}</td>
                    <td nowrap>{{ $member->meftmatch }}</td>
                    <td nowrap>{{ $member->rightmatch }}</td>
                    <td nowrap>{{ $member->matchbv }}</td>
                    <td nowrap>{{ $TotMbc }}</td>
                    <td nowrap>{{ $TotMJoin }}</td>
                    <td nowrap>{{ $Gfamount }}</td>
                    <td nowrap>{{ $repurchase->active_fun_unit }}</td>
                    <td align="center" nowrap>{{ $MunitRate }}</td>
                    <td align="center" nowrap>{{ $Amount }}</td>
                </tr>
            @endforeach

            @if ($members->isEmpty())
                <tr>
                    <td height="45" colspan="14" nowrap style="text-align:center; color:#FF0000;">No closing data!</td>
                </tr>
            @endif
        </table>
       </div>
@endsection
