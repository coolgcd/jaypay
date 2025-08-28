@extends('member.layout')

@section('title', 'Matching Income History')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Matching Income History</h4>
                    <p class="mb-0 text-sm">For Member: {{ $member->name }} ({{ $memid }})</p>
                </div>
                <div class="card-body">
                    <div class="alert alert-info bg-light-info text-info border-0 mb-4">
                        <strong>How to Read This Table:</strong> Each row shows a single payout event. It displays the new business that was generated in your legs for that specific pay cycle, how much of it was matched, and the commission you received.
                    </div>

                    @if($matchingIncome->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Date</th>
                                        <th class="text-success">New Left Business</th>
                                        <th class="text-success">New Right Business</th>
                                        <th class="text-warning">Matched Business</th>
                                        <th class="text-primary">Commission Paid (6%)</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($matchingIncome as $index => $row)
                                        @php
                                            // Calculate the new business generated for this specific cycle
                                            $newLeftBusiness = $row->tot_matching + $row->leftcarry;
                                            $newRightBusiness = $row->tot_matching + $row->rightcarry;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $matchingIncome->firstItem() + $index }}</td>
                                            <td>{{ date('d-m-Y H:i', $row->confirm_date) }}</td>
                                            <td class="text-success">₹{{ number_format($newLeftBusiness, 2) }}</td>
                                            <td class="text-success">₹{{ number_format($newRightBusiness, 2) }}</td>
                                            <td class="text-warning"><strong>₹{{ number_format($row->tot_matching, 2) }}</strong></td>
                                            <td class="text-primary"><strong>₹{{ number_format($row->payamt, 2) }}</strong></td>
                                            <td class="text-center">
                                                @if($row->status == 1)
                                                    <span class="badge bg-success">Confirmed</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">No matching income records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $matchingIncome->links() }}
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            No matching income records found for this member.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
