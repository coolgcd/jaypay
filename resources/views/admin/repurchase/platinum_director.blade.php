

@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">

<div class="container">
    <table width="100%" class="table table-bordered table-striped" style="font-size:11px;" border="1">
        <tr style="background:#FFD3A8!important;">
            <td width="5%" align="center" nowrap><strong>Sr. No.</strong></td>
            <td width="9%" nowrap><strong>MemID</strong></td>
            <td width="14%" nowrap><strong>Name</strong></td>
            <td width="4%" nowrap><strong>Platinum Director</strong></td>
            <td width="4%" nowrap><strong>Total Director</strong></td>
            <td width="2%" nowrap><strong>PGBV</strong></td>
            <td width="2%" nowrap><strong>Total Unit</strong></td>
            <td width="10%" align="center" nowrap><strong>Bike Fund 2%</strong></td>
            <td width="10%" align="center" nowrap><strong>Tot. Amount</strong></td>
        </tr>

        @if($members->count())
            @foreach($members as $key => $member)
            <tr>
                <td align="center">{{ $key + 1 }}</td>
                <td>{{ $member->mem_id }}</td>
                <td>{{ $member->name }}</td>
                <td>{{ $member->platinum_director }}</td>
                <td>{{ $member->total_director }}</td>
                <td>{{ $member->pgbv }}</td>
                <td>{{ $member->total_unit }}</td>
                <td>{{ number_format($member->bike_fund, 2) }}</td>
                <td>{{ number_format($member->total_amount, 2) }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" style="text-align:center; color:#FF0000;">No data available!</td>
            </tr>
        @endif
    </table>
</div>
</div>
@endsection
