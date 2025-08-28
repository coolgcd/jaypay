@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="block" id="block-tables">
            <div class="secondary-navigation">
                <div class="sec-name">Manage Achievers</div>
            </div>
            <div class="content">
                <div class="inner">
                    @if(session('success'))
                        <div class="flash">
                            <div class="message notice">
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @elseif(session('error'))
                        <div class="flash">
                            <div class="message warning">
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('achivers.store') }}" method="POST" class="form">
                        @csrf
                        <div class="form-group">
                            <strong>Achievers Details</strong>
                            <textarea name="achiverdel" id="achiverdel" class="form-control" cols="45" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info">Add New Achiever Details</button>
                        </div>
                    </form>

                    @if($achivers->isNotEmpty())
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>SI.No.</th>
                                <th>Achievers Details</th>
                                <th>Added Date</th>
                                <th>Delete</th>
                            </tr>
                            @foreach($achivers as $key => $achiver)
                                <tr>
                                    <td>{{ $achivers->firstItem() + $key }}</td>
                                    <td>{{ $achiver->tatitle }}</td>
                                    <td>{{ date('d-m-Y', $achiver->add_date) }}</td>
                                    <td>
                                        <form action="{{ route('achivers.destroy', $achiver->taid) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        {{ $achivers->links() }} <!-- Pagination links -->
                    @else
                        <tr>
                            <td colspan="4" class="text-center text-danger">No Data Available!</td>
                        </tr>
                    @endif
                </div>
            </div>
        </div>
       </div>
@endsection
