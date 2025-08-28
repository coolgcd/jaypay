@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="block" id="block-tables">
            <div class="secondary-navigation">
                <div class="sec-name">Manage Payment List</div>
                <div class="sec-button">
                    <div class="actions">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        <input name="button" type="button" class="button" onclick="location.href='{{ url('ReportExportExcel.php') }}'" value="Export Pending Report In Excel"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="content">
                <div class="inner">
                    @if(session('message'))
                        <div class="flash">
                            <div class="message notice">
                                <p>{{ session('message') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($paymentData->isEmpty())
                        <tr>
                            <td colspan="5">
                                <div class="flash">
                                    <div class="message warning">
                                        <p align="center" style="color:#FF0000;">No Data Available!</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SI.No.</th>
                                        <th>Memid</th>
                                        <th>Member Name</th>
                                        <th>Withdraw amount</th>
                                        <th>Closing Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentData as $index => $payment)
                                    <tr>
                                        <td>{{ (($page - 1) * $limit) + $index + 1 }}</td>
                                        <td>{{ $payment->memid }}</td>
                                        <td>{{ $payment->member_name }}</td>
                                        <td>{{ $payment->wdr_amount }}</td>
                                        <td>{{ date('d-m-Y', $payment->wdatetime) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="actions-bar">
                            {{-- <div class="pagination">
                                {{ $paymentData->links() }}
                            </div> --}}
                            <div class="clear"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
       </div>
@endsection
