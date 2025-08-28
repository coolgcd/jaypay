
@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        @php
        $startingDate = mktime(23, 50, 0, 10, 4, 2020);
        $closingDateStart = mktime(23, 50, 0, 11, 5, 2020);
        $closingDate = mktime(23, 50, 0, 11, 5, 2020);
        @endphp

        <table width="100%" class="table table-bordered table-striped" style="font-size:11px;" border="1">
            <tr style="background:#FFD3A8!important;">
                <th>Sr. No.</th>
                <th>Rec. MemID</th>
                <th>Name</th>
                <th>Self BV</th>
                <th>Team BV</th>
                <th>My Self+Team BV</th>
                <th>My Percent</th>
                <th>Self BV Income</th>
                <th>&nbsp;</th>
            </tr>

            @php
            $members = \App\Models\Member::where('activate_date', '!=', 0)->get();
            $ssn = 1;
            @endphp

            @foreach ($members as $member)
                @php
                $mySelfBv = $member->totselfpurch;
                $myTeamBV = $member->accuincome;
                $allTeamBV = $myTeamBV + $mySelfBv;

                // Calculate percentage based on total BV
                $bvPercentage = match (true) {
                    $allTeamBV > 1 && $allTeamBV <= 1000 => 5,
                    $allTeamBV > 1000 && $allTeamBV <= 5000 => 10,
                    $allTeamBV > 5000 && $allTeamBV <= 20000 => 13,
                    $allTeamBV > 20000 && $allTeamBV <= 40000 => 16,
                    $allTeamBV > 40000 && $allTeamBV <= 75000 => 20,
                    $allTeamBV > 75000 => 25,
                    default => 0,
                };

                // If the member has coins, override the BV percentage
                if ($member->coin != 0) {
                    $bvPercentage = $member->coin;
                }

                $selfBVIncome = round($mySelfBv * $bvPercentage / 100);
                @endphp

                <tr>
                    <td align="center" nowrap>{{ $ssn++ }}</td>
                    <td nowrap>{{ $member->mem_id }}</td>
                    {{-- <td nowrap>{{ getMemName($member->mem_id) }}</td> --}}
                    <td nowrap>{{ $mySelfBv }}</td>
                    <td nowrap>{{ $myTeamBV }}</td>
                    <td nowrap>{{ $allTeamBV }}</td>
                    <td nowrap>{{ $bvPercentage }} %</td>
                    <td nowrap>{{ $selfBVIncome }}</td>
                    <td nowrap>&nbsp;</td>
                </tr>

                @php
                $teamIncome = 0;
                $teamMembers = \App\Models\Member::where('sponsorid', $member->mem_id)->where('activate_date', '!=', 0)->get();
                @endphp

                @if ($teamMembers->isNotEmpty())
                    <tr>
                        <td colspan="9">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2" class="table-bordered table-striped">
                                <tr>
                                    <th>Team ID</th>
                                    <th>Name</th>
                                    <th>Tot BV</th>
                                    <th>Get %</th>
                                    <th>Different</th>
                                    <th>Team Income</th>
                                </tr>

                                @php
                                $totalTeamBV = 0;
                                @endphp

                                @foreach ($teamMembers as $teamMember)
                                    @php
                                    $teamBV = $teamMember->accuincome + $teamMember->totselfpurch;
                                    $teamPercentage = match (true) {
                                        $teamBV > 1 && $teamBV <= 1000 => 5,
                                        $teamBV > 1000 && $teamBV <= 5000 => 10,
                                        $teamBV > 5000 && $teamBV <= 20000 => 13,
                                        $teamBV > 20000 && $teamBV <= 40000 => 16,
                                        default => 0,
                                    };

                                    if ($teamMember->coin != 0) {
                                        $teamPercentage = $teamMember->coin;
                                    }

                                    $difference = $bvPercentage - $teamPercentage;
                                    $teamIncome = $teamBV > 0 ? round($teamBV * $difference / 100) : 0;
                                    $totalTeamBV += $teamBV;
                                    @endphp

                                    <tr>
                                        <td>{{ $teamMember->mem_id }}</td>
                                        {{-- <td>{{ getMemName($teamMember->mem_id) }}</td> --}}
                                        <td>{{ $teamBV }}</td>
                                        <td>{{ $teamPercentage }} %</td>
                                        <td>{{ $difference }} %</td>
                                        <td>{{ $teamIncome }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="2" align="right"><strong>Total:</strong></td>
                                    <td>{{ $totalTeamBV }}</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>{{ $teamIncome }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td colspan="8" align="left">
                        <strong>Final Income:</strong> {{ $selfBVIncome + $teamIncome }}
                    </td>
                </tr>
            @endforeach

            @if ($members->isEmpty())
                <tr>
                    <td colspan="9" align="center" style="color:#FF0000;">No closing data!</td>
                </tr>
            @endif
        </table>


       </div>
@endsection
