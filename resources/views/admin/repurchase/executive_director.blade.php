
@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container">
            <table width="100%" class="table table-bordered table-striped" style="font-size:11px;" border="1">
                <thead>
                    <tr style="background:#FFD3A8!important;">
                        <th width="5%" align="center" nowrap><strong>Sr. No.</strong></th>
                        <th width="9%" nowrap><strong>MemID</strong></th>
                        <th width="14%" nowrap><strong>Name</strong></th>
                        <th width="4%" nowrap><strong>Executive Director</strong></th>
                        <th width="4%" nowrap><strong>Total Director</strong></th>
                        <th width="4%" nowrap><strong>PGBV</strong></th>
                        <th width="5%" nowrap><strong>Travel Fund 3%</strong></th>
                        <th width="10%" align="center" nowrap><strong>Total Unit</strong></th>
                        <th width="10%" align="center" nowrap><strong>Tot. Amount</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $key => $member)
                        <tr>
                            <td align="center" nowrap>{{ $key + 1 }}</td>
                            <td nowrap>{{ $member->mem_id }}</td>
                            <td nowrap>{{ $member->name }}</td>
                            <td nowrap>{{ $member->executive_director }}</td>
                            <td nowrap>{{ $member->total_director }}</td>
                            <td nowrap>{{ $member->pgbv }}</td>
                            <td nowrap>{{ $member->travel_fund }}</td>
                            <td align="center" nowrap>{{ $member->total_unit }}</td>
                            <td align="center" nowrap>{{ $member->total_amount }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; color:#FF0000;">No closing data!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
       </div>
@endsection
