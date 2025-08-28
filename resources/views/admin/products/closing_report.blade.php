@extends('admin.layout')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Start Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Closing Report</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
                                <li class="breadcrumb-item active">Closing Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Manage Closing Report</h4>
                        </div><!-- end card header -->

                        <div class="card-body">

                            <!-- Show alert message if available -->
                            @if(session('message'))
                              <div class="myalert-unsus">
                                {{ session('message') }}
                                <i class="fa fa-exclamation-triangle"></i>
                              </div>
                            @endif

                            <div style="overflow:hidden; margin-bottom: 15px;">
                                <!-- Button to add new closing -->
                                <a href="{{ route('closing.add') }}" class="btn btn-primary" style="float:right">
                                    Add New Closing
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                      <tr style="background:#FFD3A8!important;">
                                        <th width="5%">Sr. No.</th>
                                        <th width="9%" class="text-center">Closing Date</th>
                                        <th width="72%"> </th>
                                        <th width="14%" class="text-center">Manage</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @forelse($closings as $index => $closing)
                                        <tr>
                                          <td>{{ $index + 1 }}</td>
                                          <td class="text-center">{{ date('d-m-Y', $closing->closing_date) }}</td>
                                          <td></td>
                                          <td class="text-center">
                                            <a href="{{ route('closing.details', ['closdate' => $closing->closing_date]) }}">
                                              View Details
                                            </a>
                                          </td>
                                        </tr>
                                      @empty
                                        <tr>
                                          <td colspan="4" class="text-center">No closing dates available</td>
                                        </tr>
                                      @endforelse

                                      <!-- Display new closing date if available -->
                                      @if(session('closing_date'))
                                        <tr>
                                          <td colspan="4" class="text-center text-danger">
                                            <strong>New Closing Date: </strong>
                                            {{ date('d-m-Y', session('closing_date')) }}
                                          </td>
                                        </tr>
                                      @endif
                                    </tbody>
                                </table>
                            </div>

                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div>
            </div>

        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © Velzon.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
