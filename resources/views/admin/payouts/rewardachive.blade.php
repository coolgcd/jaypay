@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="block" id="block-tables">
            <div class="secondary-navigation">
                <div class="sec-name">Member who Achieved the Reward</div>
            </div>
            <div class="content">
                <form method="GET" action="{{ route('rewards.index') }}" class="form">
                    <div class="sec-button">
                        <div class="actions">
                            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td bgcolor="#CCCCCC" style="color:#FFF; font-weight:bold;">&nbsp;</td>
                                    <td bgcolor="#CCCCCC">
                                        <input name="keyword" type="text" class="form-control" placeholder="Search by Name, Phone, Member ID" value="{{ old('keyword', $keyword) }}" />
                                    </td>
                                    <td bgcolor="#CCCCCC">
                                        <button type="submit" class="btn btn-primary">Search Member</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>

                <div class="table-responsive mt-3">
                    <table width="100%" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SI.No.</th>
                                <th>Memid</th>
                                <th>Member Name</th>
                                <th>Tot Direct</th>
                                <th>Reward Name</th>
                                <th>Add Date</th>
                                <th>Confirm Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rewards as $index => $reward)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="color: {{ $reward->confirm_date ? '#00FF00' : '#000099' }}; font-weight: bold;">
                                        {{ $reward->memid }}
                                    </td>
                                    <td>{{ \App\Http\Controllers\RewardController::getMemberName($reward->memid) }}</td>
                                    <td>{{ $reward->pvused }}</td>
                                    <td>{{ $reward->rewardimg }}</td>
                                    <td>{{ date('d-m-Y', strtotime($reward->add_date)) }}</td>
                                    <td style="background-color:#00FFFF;">
                                        {{ $reward->confirm_date ? date('d-m-Y', strtotime($reward->confirm_date)) : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center" style="color: #FF0000;">No any member achieved the reward!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $rewards->links() }} <!-- Laravel Pagination links -->
            </div>
        </div>
       </div>
@endsection
