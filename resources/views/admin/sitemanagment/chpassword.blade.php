@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="graphs">
            <h3 class="blank1">Change Password</h3>
            <div class="xs">
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

                <form class="form-horizontal" method="POST" name="frmcardded" id="frmcardded" enctype="multipart/form-data" onsubmit="return Validation()">
                    @csrf
                    <div style="border:solid 1px #ccc; padding:20px 0px; margin:10px 0px;">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Old Password</label>
                                <div class="col-sm-3">
                                    <input name="oldpass" type="password" class="form-control1" id="oldpass" placeholder="Old Password">
                                </div>
                                <label class="col-sm-2 control-label">New Password</label>
                                <div class="col-sm-3">
                                    <input name="newpass" type="password" class="form-control1" id="newpass" placeholder="New Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Confirm New Password</label>
                                <div class="col-sm-3">
                                    <input name="newpass_confirmation" type="password" class="form-control1" id="newpass_confirmation" placeholder="Confirm New Password">
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

        <script>
        function Validation() {
            let error = 0;

            if (document.frmcardded.oldpass.value == "") {
                document.getElementById("oldpass").style.borderColor = "red";
                error = 1;
            } else {
                document.getElementById("oldpass").style.borderColor = "black";
            }

            if (document.frmcardded.newpass.value == "") {
                document.getElementById("newpass").style.borderColor = "red";
                error = 1;
            } else {
                document.getElementById("newpass").style.borderColor = "black";
            }

            if (document.frmcardded.newpass_confirmation.value == "") {
                document.getElementById("newpass_confirmation").style.borderColor = "red";
                error = 1;
            } else {
                document.getElementById("newpass_confirmation").style.borderColor = "black";
            }

            if (error == 1) {
                return false;
            }
        }
        </script>
       </div>
@endsection
