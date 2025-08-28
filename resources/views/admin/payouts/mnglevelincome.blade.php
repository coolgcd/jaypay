@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            <form action="{{ route('level.income.search') }}" method="post" enctype="multipart/form-data" name="frmSubscriber" id="frmSubscriber">
                @csrf

                @if (session('errorMsg'))
                <div class="myalert-unsus">
                    {{ session('errorMsg') }}
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                @endif

                <h3 class="blank1">Manage Level Income</h3>
                <div style="overflow:hidden;">
                    <div class="actions">
                        <table width="100%" border="0" cellspacing="1" cellpadding="1">
                            <tr>
                                <td bgcolor="#CCCCCC" style="color:#FFF; font-weight:bold;">&nbsp;</td>
                                <td bgcolor="#CCCCCC">
                                    <input name="keyword" type="text" class="text_field" id="keyword" placeholder="Search by Name, Phone, Member ID" style="width:350px; background:#f5f5f5;" value="{{ $keyword ?? '' }}" />
                                </td>
                                <td bgcolor="#CCCCCC">
                                    <input name="Submit" type="submit" class="button" value="Search Member" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="xs">
                    <div class="table-responsive">
                        <table width="100%" class="table table-bordered table-striped">
                            <thead>
                                <tr style="background:#FFD3A8!important;">
                                    <th width="5%"><strong>Sr. No.</strong></th>
                                    <th width="4%"><strong>Rec. MemID</strong></th>
                                    <th width="5%"><strong>Name</strong></th>
                                    <th width="13%"><strong>From MemID</strong></th>
                                    <th width="14%"><strong>Name</strong></th>
                                    <th width="13%"><strong>Level</strong></th>
                                    <th width="7%"><strong>Rec Date</strong></th>
                                    <th width="7%"><strong>Confirm Date</strong></th>
                                    <th width="11%"><strong>Amount</strong></th>
                                    <th width="12%"><strong>Status</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($levelIncomes as $index => $income)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $income->memid }}</td>
                                    <td>{{ getMemName($income->memid) }}</td>
                                    <td>{{ $income->fromid }}</td>
                                    <td>{{ getMemName($income->fromid) }}</td>
                                    <td>Level - {{ $income->levid }}</td>
                                    <td>{{ date('d-m-Y', strtotime($income->rec_date)) }}</td>
                                    <td>{{ $income->calcdate ? date('d-m-Y', strtotime($income->calcdate)) : 'N/A' }}</td>
                                    <td align="center">{{ $income->rec_amt }}</td>
                                    <td align="center" style="background-color: {{ $income->status == 1 ? '#00FF00' : '#FF00FF' }};"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($levelIncomes->isEmpty())
                        <tr>
                            <td colspan="10" class="text-center text-danger">No level income found!</td>
                        </tr>
                        @endif
                    </div>
                </div>
                <div class="actions-bar">
                    <div class="pagination">
                        {{ $levelIncomes->links() }}
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
       </div>
@endsection
