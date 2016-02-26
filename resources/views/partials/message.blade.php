{{-- STATUS MESSAGE: if there are any errors, show them here --}}
@if(Session::has('message'))
    @if(Session::get('msgType') === 'danger')
    <div class="alert alert-centered alert-dismissable alert-danger"> 
    @elseif(Session::get('msgType') === 'success')
    <div class="alert alert-centered alert-dismissable alert-success">
    @else            
    <div class="alert alert-centered alert-dismissable alert-info">
    @endif
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('message') }}
        {{ Session::forget('message') }} 
        {{ Session::forget('msgType') }}
    </div>
@endif


