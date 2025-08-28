@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Manage Joining Package</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                <li class="breadcrumb-item active">Manage Joining Package</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- start card -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Joining Packages List</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SI.No.</th>
                                            <th>Joining Package</th>
                                            <th>MRP</th>
                                            <th>DP Price</th>
                                            <th>BV Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $index => $product)
                                            <tr class="{{ $index % 2 == 0 ? 'even' : 'odd' }}">
                                                <td>{{ $products->firstItem() + $index }}</td>
                                                <td>{{ $product->pro_title }}</td>
                                                <td align="center">{{ $product->pro_price }}</td>
                                                <td align="center">{{ $product->dpprice }}</td>
                                                <td align="center">{{ $product->bv_value }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="flash">
                                                        <div class="message warning">
                                                            <p align="center" style="color:#FF0000;">No Data Available!</p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            @if($products->hasPages())
                                <div class="actions-bar">
                                    <div class="pagination">
                                        {{ $products->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            @endif
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div>
            <!-- end row -->

        </div><!-- container-fluid -->
    </div><!-- page-content -->

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © YourCompany.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by YourCompany
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div><!-- main-content -->
@endsection
