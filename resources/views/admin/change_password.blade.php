@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="graphs">
        <h3 class="blank1">Change Password</h3>
        <div class="xs">
            <form class="form-horizontal" method="POST" action="{{ route('change.password') }}" id="frmcardded">
                @csrf

                <div style="border:solid 1px #ccc; padding:20px 0px; margin:10px 0px;">
                    <fieldset>
                        @if(session('error'))
                            <p style="color:#FF0000; font-size:14px; font-weight:bold;">{{ session('error') }}</p>
                        @elseif(session('success'))
                            <p style="color:#003300; font-size:14px; font-weight:bold;">{{ session('success') }}</p>
                        @endif

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Old Password</label>
                            <div class="col-sm-3">
                                <input name="oldpass" type="password" class="form-control1" id="oldpass" placeholder="Old Password">
                                @error('oldpass')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>

                            <label class="col-sm-2 control-label">New Password</label>
                            <div class="col-sm-3">
                                <input name="newpass" type="password" class="form-control1" id="newpass" placeholder="New Password">
                                @error('newpass')
                                    <p style="color:red;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <hr />

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <input type="submit" value="Submit" class="btn btn-danger">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </form>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script>
    document.getElementById('frmcardded').onsubmit = function () {
    let oldPass = document.getElementById('oldpass');
    let newPass = document.getElementById('newpass');
    let valid = true;

    if (oldPass.value === "") {
        oldPass.style.borderColor = "red";
        valid = false;
    } else {
        oldPass.style.borderColor = "black";
    }

    if (newPass.value === "") {
        newPass.style.borderColor = "red";
        valid = false;
    } else {
        newPass.style.borderColor = "black";
    }

    return valid;
};

</script>
@endsection
