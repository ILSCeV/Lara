{{-- Needs variable: $current_section --}}
@extends('layouts.master')

@section('title')
	{{ trans('mainLang.management') }}: #{{ $current_section->id }} - {!! $current_section->title !!}
@stop

@section('content')

@if(Session::has('userGroup') AND Session::get('userGroup') == 'admin')

	<div class="panel panel-info">
		<div class="panel-heading">
			<h4 class="panel-title">#{{ $current_section->id }}: "{!! $current_section->title !!}" </h4>
		</div>

		<div class="panel panel-body no-padding">
			<table class="table table-hover">
				{!! Form::open(  array( 'route' => ['section.update', $current_section->id],
		                                'id' => $current_section->id, 
		                                'method' => 'PUT', 
		                                'class' => 'section')  ) !!}
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.section') }}:</i>
						</td>
						<td>
							{!! Form::text('id', 
							   $current_section->id, 
							   array('id'=>'id', 'hidden')) !!}

							{!! Form::text('title', 
							   $current_section->title, 
							   array('id'=>'title')) !!}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.color') }}:</i>
						</td>
						<td>
							{!! Form::input('text','color', 
							   $current_section->color, 
							   array('id'=>'color')) !!}
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<a href="../section/{{ $current_section->id }}"
							   class="btn btn-small btn-danger"
							   data-toggle="tooltip"
			                   data-placement="bottom"
			                   title="&#39;&#39;{!! $current_section->title !!}&#39;&#39; (#{{ $current_section->id }}) löschen"
							   data-method="delete"
							   data-token="{{csrf_token()}}"
							   rel="nofollow"
							   data-confirm="{{ trans('mainLang.deleteConfirmation') }} &#39;&#39;{!! $current_section->title !!}&#39;&#39; (#{{ $current_section->id }})? {{ trans('mainLang.warningNotReversible') }}">
								   	{{ trans('mainLang.delete') }}?
							</a>
							<button type="reset" class="btn btn-small btn-default">{{ trans('mainLang.reset') }}</button>
					    	<button type="submit" class="btn btn-small btn-success">{{ trans('mainLang.update') }}</button>
						</td>
					</tr>
				{!! Form::close() !!}
			</table>
		</div>
	</div>

@else
	@include('partials.accessDenied')
@endif

@stop



