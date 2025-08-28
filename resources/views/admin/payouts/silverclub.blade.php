@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="block" id="block-tables">
            <div class="secondary-navigation">
                <div class="sec-name">Silver Club Member</div>
            </div>
            <div class="content">
                <form name="manageJournal" method="GET" class="form">
                    <div class="sec-button">
                        <div class="actions">
                            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td bgcolor="#CCCCCC" style="color:#FFF; font-weight:bold;">&nbsp;</td>
                                    <td bgcolor="#CCCCCC">
                                        <input name="keyword" type="text" class="text_field" id="keyword" placeholder="Search by Name, Phone, Member ID" value="{{ $keyword }}" style="width:350px; background:f5f5f5;" />
                                    </td>
                                    <td bgcolor="#CCCCCC">
                                        <input name="Submit" type="submit" class="button" value="Search Member" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="clear"></div>
                </form>

                @if(session('message'))
                    <div class="flash">
                        <div class="message notice">
                            <p>{{ session('message') }}</p>
                        </div>
                    </div>
                @endif

                <table width="100%" class="table">
                    <tr>
                        <th width="4%" align="center" nowrap="nowrap" class="first">SI.No.</th>
                        <th align="left" nowrap="nowrap">Memid</th>
                        <th align="left" nowrap="nowrap">Member Name</th>
                        <th align="left" nowrap="nowrap">Tot Direct</th>
                        <th align="center" nowrap="nowrap">Achieve Target</th>
                        <th nowrap="nowrap" class="last">&nbsp;</th>
                    </tr>
                    @foreach($members as $index => $member)
                        <tr class="{{ $index % 2 === 0 ? 'even' : '' }}">
                            <td align="center">{{ $members->firstItem() + $index }}</td>
                            <td style="color: {{ $member->expiry_date == 1 ? '#00FF00' : '#000099' }}; font-weight:bold;">
                                <a href="javascript:void(0)" onclick="PrintInvoiceStaff('{{ $member->mem_id }}')">{{ $member->mem_id }}</a>
                                <a href="javascript:void(0)" onclick="SendMessage('{{ $member->mem_id }}')">
                                    <img src="{{ asset('images/b_tblexport.png') }}" width="16" height="16" />
                                </a>
                            </td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->tot_ref }}</td>
                            <td align="center">{{ date('d-m-Y', $member->promotdate) }}</td>
                            <td align="center">&nbsp;</td>
                        </tr>
                    @endforeach
                    @if($members->isEmpty())
                        <tr>
                            <td colspan="6">
                                <div class="flash">
                                    <div class="message warning">
                                        <p align="center" style="color:#FF0000;">No any member get 10 direct sales!</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                </table>

                <div class="actions-bar">
                    <div class="pagination">
                        {{ $members->withQueryString()->links() }}
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <script language="javascript">
            function SendMessage(memid) {
                window.open("{{ url('send-message') }}/" + memid, "_blank", "toolbar=no, scrollbars=no, resizable=no, top=500, left=500, width=400, height=400");
            }

            function PrintInvoiceStaff(memid) {
                window.open("{{ url('invoice') }}/" + memid, "_blank", "toolbar=yes,scrollbars=yes,toolbar=no,resizable=yes,top=0,left=100,width=500,height=700");
            }
        </script>
       </div>
@endsection
