@extends('admin.layout')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Closing Calculation</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                <li class="breadcrumb-item active">Closing Calculation</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            @if($MSG)
            <div class="alert alert-warning">
                {{ $MSG }}
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            @endif

            <!-- Calculation Options Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Calculation Options</h4>
                        </div>
                        <div class="card-body">
                            <div style="overflow:hidden">
                                <a href="javascript:void(0);"
                                   onclick="window.open('{{ url('calc/master_repurchase') }}', '_blank',
                                   'toolbar=no,scrollbars=yes,resizable=yes,top=0,left=100,width=880,height=900');"
                                   class="btn btn-default">BV Master Calculation</a>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="background:#FFD3A8;">
                                            <th colspan="4" align="center">Calculation Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td align="center">Level Income</td>
                                            <td align="center">
                                                <a href="javascript:void(0);" onclick="window.open('{{ url('calc/masterlevelIncome') }}', '_blank', 'width=880,height=900');" class="btn btn-default">Calculate</a>
                                            </td>
                                            <td align="center">Silver Club Income</td>
                                            <td align="center">
                                                <a href="javascript:void(0);" onclick="window.open('{{ url('calc/updsilverIncome') }}', '_blank', 'width=880,height=900');" class="btn btn-default">Calculate -1</a>
                                                <a href="javascript:void(0);" onclick="window.open('{{ url('calc/masterSilverClub') }}', '_blank', 'width=880,height=900');" class="btn btn-default">Calculate -2</a>
                                            </td>
                                        </tr>
                                        <!-- Repeat for other rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Calculation Results</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SI.No.</th>
                                            <th>Closing Date</th>
                                            <th>Total Joining Amount</th>
                                            <th>Recharge Amount</th>
                                            <th>Repurchase BV</th>
                                            <th>Total Turnover</th>
                                            <th>Global (10%)</th>
                                            <th>Active (5%)</th>
                                            <th>Leader (15%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($results as $result)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::createFromTimestamp($result->closing_date)->format('d-m-Y') }}</td>
                                            <td>{{ $result->totjoingamt }}</td>
                                            <td>{{ $result->rechargeamt }}</td>
                                            <td>{{ $result->compshowbv }}</td>
                                            <td>{{ $result->totcompanybv }}</td>
                                            <td>{{ $result->globalamt }}</td>
                                            <td>{{ $result->active_fund }} + {{ $result->totjoingamt * 5 / 100 }} = {{ $result->active_fund + ($result->totjoingamt * 5 / 100) }}</td>
                                            <td>{{ $result->leadershipb }}/{{ $result->totleader }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-danger">No Data Available!</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © YourApp.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by YourBrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
