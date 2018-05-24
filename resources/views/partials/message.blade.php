{{-- STATUS MESSAGE: if there are any errors, show them here --}}
@if(Session::has('message'))
    @php
        $messageType = Session::get('msgType') ? Session::get('msgType') : 'info'
    @endphp
    <div class="alert alert-centered alert-dismissable alert-fixed alert-{{$messageType}}">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('message') }}
        @php
            Session::forget('message');
            Session::forget('msgType');
        @endphp
    </div>
@endif


