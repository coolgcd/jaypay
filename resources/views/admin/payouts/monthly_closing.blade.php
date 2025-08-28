@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            @if(session('errorMsg'))
                <div class="myalert-unsus">{{ session('errorMsg') }} <i class="fa fa-exclamation-triangle"></i></div>
            @endif

            <div class="secondary-navigation">
                <form action="{{ route('monthly_closing.index') }}" method="post" enctype="multipart/form-data">
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
                                                <option value="" selected="selected">Select closing</option>
                                                @foreach ($monthlyClosings as $closing)
                                                    <option value="{{ $closing->closing_date }}" {{ session('ClosSession') == $closing->closing_date ? 'selected' : '' }}>
                                                        {{ date('d-m-Y', $closing->closing_date) }}
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
                    <form id="frmSubscriber" name="frmSubscriber" method="post" action="{{ route('monthly_closing.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="hdnclosing" value="1" />
                        <input type="submit" name="payconfirm" id="payconfirm" value="Confirm Payout" class="btn btn-default" />

                        <table width="100%" class="table table-bordered table-striped">
                            <tr>
                                <td colspan="14" nowrap><strong>Results</strong></td>
                            </tr>
                            @foreach ($monthlyClosings as $closing)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $closing->memid }}</td>
                                    <td>{{ getMemName($closing->memid) }}</td>
                                    <td>{{ date('h:i d-m-Y', $closing->closing_date) }}</td>
                                    <td>{{ $closing->reward_income }}</td>
                                    <td>{{ $closing->level_income }}</td>
                                    <td>{{ $closing->silver_club_income }}</td>
                                    <td>{{ $closing->upline_income }}</td>
                                    <td>{{ $closing->downline_income }}</td>
                                    <td>{{ $closing->total }}</td>
                                    <td>{{ $closing->final_amount }}</td>
                                    <td>{{ $closing->tds }}</td>
                                    <td>{{ $closing->admin }}</td>
                                    <td>{{ $closing->net_amount }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </form>
                </div>
            </div>
        </div>
       </div>
@endsection
