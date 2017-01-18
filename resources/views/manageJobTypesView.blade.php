{{-- Needs variables: jobtypes --}}

@extends('layouts.master')

@section('title')
	Verwaltung: Diensttypen
@stop

@section('content')

@if(Session::has('userGroup')
	AND (Session::get('userGroup') == 'marketing'
	OR Session::get('userGroup') == 'clubleitung'
	OR Session::get('userGroup') == 'admin'))

	<div class="panel panel-info">
		<div class="panel-heading">
				<h4 class="panel-title">Verwaltung: Diensttypen</h4>
		</div>		
		<table class="table info table-hover table-condensed">
			<thead>
				<tr class="active">
					<th>
						&nbsp;
					</th>
					<th>
						ID
					</th>
					<th class="col-md-3">
						Dienst
					</th>
					<th class="col-md-2">
						Dauer
					</th>
					<th class="col-md-1">
						Statistische Wertung
					</th>
					<th class="col-md-5">
						Aktionen
					</th>
				</tr>
			</thead>
			<tbody>

				<div class="container">
					@foreach($jobtypes as $jobtype)
						<tr>
							<td>
								&nbsp;
							</td>
							<td>
								{!! $jobtype->id !!}
							</td>
							<td>
						      	<a href="../jobtype/{{ $jobtype->id }}">
						      		{!! $jobtype->jbtyp_title !!}
						      	</a>
							</td>
							<td>						
								{!! date("H:i", strtotime($jobtype->jbtyp_time_start)) !!}
								-
								{!! date("H:i", strtotime($jobtype->jbtyp_time_end)) !!}
							</td>
							<td>
								{!! $jobtype->jbtyp_statistical_weight !!}
							</td>
							<td>
								<a href="../jobtype/{{ $jobtype->id }}"
								   class="btn btn-small btn-success"
								   rel="nofollow">
								   	Details ansehen und anpassen
								</a>
								&nbsp;&nbsp;
								<a href="../jobtype/{{ $jobtype->id }}"
								   class="btn btn-small btn-danger"
								   data-method="delete"
								   data-token="{{csrf_token()}}"
								   rel="nofollow"
								   data-confirm="Möchtest du &#39;&#39;{!! $jobtype->jbtyp_title !!}&#39;&#39; (#{{ $jobtype->id }}) wirklich löschen? Diese Aktion kann man nicht rückgängig machen!">
								   	Diesen Diensttyp entfernen
								</a>
							</td>
						</tr>
					@endforeach
				</div>
			</tbody>
		</table>
	</div>
		
	<center>
		{{ $jobtypes->links() }}
	</center>

	<br/>
@else
	@include('partials.accessDenied')
@endif
@stop



