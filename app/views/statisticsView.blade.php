{{-- Needs variables: TBA --}}

@extends('layouts.master')

@section('title')
	Dienstestatistik
@stop

@section('content')

@if(Session::has('userId'))

	<div class="panel">
		<div class="panel-heading">
			<h4 class="panel-title">Anzahl gemachter Dienste im Januar 2015</h4>
		</div>
		<br>
		<div class="panel-body">	
					@foreach($persons as $person)
						{{{ $person->prsn_name }}} ({{{ $person->getClub->clb_title }}})&nbsp; 
							<div style="position:absolute; background-color: #82CFFD; 
							width: {{ 5 + 40 * $person->total }}px; 
							height: 23px;">&nbsp;&nbsp;&nbsp;{{ $person->total }}</div>
						<br><br><br>
					@endforeach

		</div>
	</div>


@else
	@include('partials.accessDenied')
@endif
@stop



