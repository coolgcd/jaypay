@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <form name="manageJournal" method="post" class="form">
            @csrf
            <div class="block" id="block-tables">
                <div class="secondary-navigation">
                    <div class="sec-name">Member who Income with Downline</div>
                </div>
                <div class="content">
                    <div class="sec-button">
                        <div class="actions">
                            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td bgcolor="#CCCCCC" style="color:#FFF; font-weight:bold;">&nbsp;</td>
                                    <td bgcolor="#CCCCCC">
                                        <input name="keyword" type="text" class="text_field" id="keyword" placeholder="Search by Name, Phone, Member ID" style="width:350px; background:f5f5f5;" value="{{ $keyword }}" />
                                    </td>
                                    <td bgcolor="#CCCCCC">
                                        <input name="Submit" type="submit" class="button" value="Search Member" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="inner">
                        @if(session('message'))
                        <div class="flash">
                            <div class="message notice">
                                <p>{{ session('message') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="xs">
                            <div class="table-responsive">
                                <table width="100%" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="4%" align="center" class="first">SI.No.</th>
                                            <th align="center">Memid</th>
                                            <th align="left">Member Name</th>
                                            <th align="center">Received Date</th>
                                            <th align="center">Total Amount</th>
                                            <th align="center">Received Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($downlineIncomes->isNotEmpty())
                                            @foreach($downlineIncomes as $index => $income)
                                                <tr class="{{ ($index % 2 == 0) ? 'even' : 'odd' }}">
                                                    <td align="center">{{ $downlineIncomes->firstItem() + $index }}</td>
                                                    <td align="center" style="color:{{ $income->confirm_date ? '#00FF00' : '#000099' }}; font-weight:bold;">{{ $income->memid }}</td>
                                                    <td>{{ getMemName($income->memid) }}</td>
                                                    <td align="center">{{ $income->rec_date ? \Carbon\Carbon::createFromTimestamp($income->rec_date)->format('d-m-Y') : 'N/A' }}</td>
                                                    <td align="center">{{ $income->totamt }}</td>
                                                    <td align="center" style="background-color:#00FFFF;">{{ $income->rec_amt }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6">
                                                    <div class="flash">
                                                        <div class="message warning">
                                                            <p align="center" style="color:#FF0000;">No any member achieve the reward!</p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $downlineIncomes->links() }} <!-- Pagination links -->
                    </div>
                </div>
            </div>
        </form>
        <script>
            function SendMessage(memid) {
                window.open("{{ url('SendMessage') }}?Nmemid=" + memid, "_blank", "toolbar=no, scrollbars=no, resizable=no, top=500, left=500, width=400, height=400");
            }

            function PrintInvoiceStaff(memid) {
                window.open("{{ url('Invoice') }}?MEMID=" + memid, "_blank", "toolbar=yes,scrollbars=yes,toolbar=no,resizable=yes,top=0,left=100,width=500,height=700");
            }
        </script>
       </div>
@endsection
