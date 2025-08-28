
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
                    <td width="4%" nowrap><strong>Crown Ambassador</strong></td>
                    <td width="4%" nowrap><strong>Total Director</strong></td>
                    <td width="4%" nowrap><strong>PGBV</strong></td>
                    <td width="2%" align="center" nowrap><strong>Total Unit</strong></td>
                    <td width="3%" align="center" nowrap><strong>Royality 2%</strong></td>
                    <td width="10%" align="center" nowrap><strong>Tot. Amount</strong></td>
                </tr>

                @forelse($members as $key => $member)
                <tr>
                    <td align="center">{{ $key + 1 }}</td>
                    <td>{{ $member->mem_id }}</td> <!-- Replace with the actual attribute name -->
                    <td>{{ $member->name }}</td> <!-- Replace with the actual attribute name -->
                    <td>{{ $member->crown_ambassador }}</td> <!-- Replace with the actual attribute name -->
                    <td>{{ $member->total_director }}</td> <!-- Replace with the actual attribute name -->
                    <td>{{ $member->pgbv }}</td> <!-- Replace with the actual attribute name -->
                    <td align="center">{{ $member->total_unit }}</td> <!-- Replace with the actual attribute name -->
                    <td align="center">{{ $member->royality }}</td> <!-- Replace with the actual attribute name -->
                    <td align="center">{{ $member->total_amount }}</td> <!-- Replace with the actual attribute name -->
                </tr>
                @empty
                <tr>
                    <td height="45" colspan="9" nowrap style="text-align:center; color:#FF0000;">No closing data!</td>
                </tr>
                @endforelse
            </table>
        </div>
       </div>
@endsection
