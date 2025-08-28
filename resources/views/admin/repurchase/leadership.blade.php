
@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <table width="100%" class="table table-bordered table-striped" style="font-size:11px;" border="1">
            <tr style="background:#FFD3A8!important;">
                <td align="center" nowrap>
                    <strong>Sr. No.</strong>
                    <input name="hdnMailList" id="hdnMailList2" type="hidden">
                </td>
                <td width="3%" nowrap><strong>Rec. MemID</strong></td>
                <td width="4%" nowrap><strong>Name</strong></td>
                <td width="7%" nowrap>CTO ({{ $Mcto }})</td>
                <td width="5%" nowrap><strong>LeaderShip ({{ $leadership }})</strong></td>
                <td width="5%" nowrap><strong>MemberBV</strong></td>
                <td width="10%" align="center" nowrap><strong>BV Unit</strong></td>
                <td width="10%" align="center" nowrap><strong>Rate</strong></td>
                <td width="10%" align="center" nowrap><strong>Tot. Amount</strong></td>
            </tr>

            @if ($members->isNotEmpty())
                @foreach ($members as $index => $member)
                    @php
                        $PGBV = $member->selfpurchase + $member->leftbv + $member->rightbv + $member->leadershipbbv;
                        $BvUnit = intval($PGBV / 1000);
                        $Amount = $BvUnit * $Mntrate;
                    @endphp
                    <tr>
                        <td width="3%" align="center" nowrap>{{ $index + 1 }}</td>
                        <td nowrap>{{ $member->mem_id }}</td>
                        <td nowrap>{{ getMemName($member->mem_id) }}</td>
                        <td nowrap>{{ $Mcto }}</td>
                        <td nowrap><strong>{{ $leadership }}</strong></td>
                        <td nowrap>{{ $PGBV }}</td>
                        <td align="center" nowrap>{{ $BvUnit }}</td>
                        <td align="center" nowrap>{{ $Mntrate }}</td>
                        <td align="center" nowrap>{{ $Amount }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td align="center" nowrap>&nbsp;</td>
                    <td nowrap>&nbsp;</td>
                    <td nowrap>&nbsp;</td>
                    <td nowrap>&nbsp;</td>
                    <td nowrap>&nbsp;</td>
                    <td nowrap>&nbsp;</td>
                    <td align="center" nowrap>{{ $members->sum(function($member) {
                        return intval(($member->selfpurchase + $member->leftbv + $member->rightbv + $member->leadershipbbv) / 1000);
                    }) }}</td>
                    <td align="center" nowrap>&nbsp;</td>
                    <td align="center" nowrap>&nbsp;</td>
                </tr>
            @else
                <tr>
                    <td height="45" colspan="9" nowrap style="text-align:center; color:#FF0000;" onclick="location.href='{{ url('admin/mngclosingReport?opr=paid') }}'">No closing data!</td>
                </tr>
            @endif
        </table>
       </div>
@endsection
