@extends('admin.layout')

@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="block" id="block-forms">
            <div class="secondary-navigation">
                <div class="sec-name">Send Message</div>
                <div class="clear"></div>
            </div>
            <div class="content">
                <div class="inner">
                    @if(session('sendsuccess'))
                        <div class="flash">
                            <div class="message notice">
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('send.message.send') }}" method="post" enctype="multipart/form-data" name="aJournal" class="form" onsubmit="return validate_frm(this)">
                        @csrf
                        <div class="group">
                            <div class="fieldWithErrors">
                                <label class="label">SMS</label>
                            </div>
                            <p>
                                <textarea name="message" cols="30" rows="5" id="message" class="form-control">{{ old('message') }}</textarea>
                            </p>
                            <p><br />
                                <span>(Note: Message length maximum 160 characters. If you type more than 160 characters, it will count as 2 messages from your credits.)</span>
                            </p>
                            <p>If you need to add the name of the member, please add &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#NAME# </p>
                        </div>
                        <div class="group navform">
                            <input type="submit" name="save" class="btn btn-danger" value="Save SMS &rarr;" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        function validate_frm(a) {
            if (document.aJournal.message.value == "") {
                alert("Please Input Message.");
                document.aJournal.message.focus();
                return false;
            }
        }
        </script>
@endsection
