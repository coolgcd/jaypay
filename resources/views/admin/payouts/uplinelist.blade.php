@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <form name="manageJournal" method="post" class="form">
            @csrf
            <div class="block" id="block-tables">
                <div class="secondary-navigation">
                    <div class="sec-name">Member who Income with Upline</div>
                </div>
                <div class="content">
                    <div class="sec-button">
                        <div class="actions">
                            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td bgcolor="#CCCCCC" style="color:#FFF; font-weight:bold;">&nbsp;</td>
                                    <td bgcolor="#CCCCCC">
                                        <input name="keyword" type="text" class="text_field" id="keyword" placeholder="Search by Name, Phone, Member ID" style="width:350px; background:f5f5f5;" value="{{ $keywords }}" />
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
                        @if(session('Msg'))
                        <div class="flash">
                            <div class="message notice">
                                <p>{{ session('Msg') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="xs">
                            <div class="table-responsive">
                                <table width="100%" class="table table-bordered table-striped">
                                    <tr>
                                        <th width="4%" align="center" nowrap="nowrap" class="first">SI.No.</th>
                                        <th align="center" nowrap="nowrap">Memid</th>
                                        <th align="left" nowrap="nowrap">Member Name </th>
                                        <th align="center" nowrap="nowrap">Received Date</th>
                                        <th align="center" nowrap="nowrap">Total Amount</th>
                                        <th align="center" nowrap="nowrap">Received Amount</th>
                                    </tr>

                                    @forelse($uplineIncomes as $key => $income)
                                        <tr class="{{ $key % 2 == 0 ? 'even' : '' }}">
                                            <td align="center">{{ $loop->iteration + ($uplineIncomes->currentPage() - 1) * $uplineIncomes->perPage() }}</td>
                                            <td align="center" nowrap="nowrap" style="color: {{ $income->confirm_date ? '#00FF00' : '#000099' }}; font-weight:bold;">{{ $income->memid }}</td>
                                            <td>{{ getMemName($income->memid) }}</td>
                                            <td align="center">{{ $income->rec_date ? date('d-m-Y', $income->rec_date) : 'N/A' }}</td>
                                            <td align="center">{{ $income->totamt }}</td>
                                            <td align="center" style="background-color:#00FFFF;">{{ $income->rec_amt }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="flash">
                                                    <div class="message warning">
                                                        <p align="center" style="color:#FF0000;">No any member achieve the reward!</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </div>

                        {{ $uplineIncomes->appends(['keyword' => $keywords])->links() }} <!-- Laravel pagination links -->
                    </div>
                </div>
            </div>
        </form>
        <script>
            function SendMessage(memid) {
                window.open("SendMessage.php?Nmemid=" + memid, "_blank", "toolbar=no, scrollbars=no, resizable=no, top=500, left=500, width=400, height=400");
            }

            function PrintInvoiceStaff(memid) {
                window.open("Invoice.php?MEMID=" + memid, "_blank", "toolbar=yes,scrollbars=yes,toolbar=no,resizable=yes,top=0,left=100,width=500,height=700");
            }
        </script>
       </div>
@endsection
