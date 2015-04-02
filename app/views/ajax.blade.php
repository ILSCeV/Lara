@extends('layouts.master')

@section('title')
	VedSt Home: current status
@stop

@section('content')

<div class="panel">
	<div class="panel-heading"><h5>testing ajax</h5></div>
	<div class="panel-body">
		
	{{-- TESTING AJAX --}}

		<div class="container">
		  <form action="/user" method="post" class="form-signin" role="form">
		    <div id="username-group" class="input-group">
		      <input id="input-test" type="text" name="username" class="form-control" placeholder="Username" required autofocus>
		      <span class="input-group-addon" id="username-addon"></span>
		    </div>
		  </form>
		</div> <!-- /container -->

	{{-- END OF TESTING --}}


	</div>
</div>

@stop