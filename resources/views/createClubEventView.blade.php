@extends('layouts.master')

@section('title')
	{{ trans('mainLang.createNewVEvent') }}
@stop

@section('content')

@if(Session::has('userId'))
	
	{!! Form::open(['method' => 'POST', 'route' => ['event.store']]) !!}

	<div class="row">
		<div class="panel col-md-6 col-sm-12 col-xs-12 no-padding">

			<div class="panel-heading">
				<h4 class="panel-title">{{ trans('mainLang.createNewEvent') }}:</h4>
			</div>

			<br>
			
			<div class="panel-body no-padding">

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="templateName" class="control-label col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.template') }}: &nbsp;</label>
					
					<div class="col-md-6 col-sm-6 col-xs-9">
						@if (isset($activeTemplate))
							{!! Form::text('templateName', $activeTemplate, array('id'=>'templateName', 'class'=>'col-xs-10 col-md-9') ) !!}
						@else
							{!! Form::text('templateName', '', array('id'=>'templateName', 'class'=>'col-xs-10 col-md-9') ) !!}
						@endif
						<span class="hidden-xs">&nbsp;&nbsp;</span>
						<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
					        <span class="caret"></span>
					    </a>
					    <ul class="dropdown-menu">
						    @foreach($templates as $template)
						        <li> 
						        	<a href="javascript:void(0);" 
						        	   onClick="document.getElementById('templateName').value='{{$template->schdl_title}}',
						        	   			window.location.href='{{ Request::getBasePath() }}/event/{{ substr($date, 6, 4) }}/{{ substr($date, 3, 2) }}/{{ substr($date, 0, 2) }}/{{ $template->id }}/create';">
						        	   {{ $template->schdl_title }}</a>
						        </li>
							@endforeach
					    </ul>
				    </div>
			   	</div>

			   	<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding col-md-offset-2 col-sm-offset-2 col-xs-offset-1">			
					{!! Form::checkbox('saveAsTemplate', '1', false, array('class'=>'col-md-1 col-sm-1 col-xs-1')) !!}
					{{ trans('mainLang.templateNewSaveQ') }}
			   	</div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			      	<label for="title" class="col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.title') }}:</label>
		      		@if(is_null($title))
			      		{!! Form::text( 'title', '', array('placeholder'=>Lang::get('mainLang.placeholderTitleWineEvening'),
			      										   'style'=>'cursor: auto',
			      										   'class'=>'col-md-9 col-sm-9 col-xs-8',
			      										   'required') ) !!}
			      	@else
			      		{!! Form::text( 'title', $title, array('placeholder'=>Lang::get('mainLang.placeholderTitleWineEvening'),
		      										   'style'=>'cursor: auto',
		      										   'class'=>'col-md-9 col-sm-9 col-xs-8',
		      										   'required') ) !!}
		      		@endif
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="subtitle" class="col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.subTitle') }}:</label>
					@if(is_null($subtitle))
						{!! Form::text('subtitle', '', array('placeholder'=>Lang::get('mainLang.placeholderSubTitleWineEvening'),
															 'class'=>'col-md-9 col-sm-9 col-xs-8',
			      										     'style'=>'cursor: auto') ) !!}
			      	@else
			      		{!! Form::text('subtitle', $subtitle, array('placeholder'=>Lang::get('mainLang.placeholderSubTitleWineEvening'),
															 'class'=>'col-md-9 col-sm-9 col-xs-8',
			      										     'style'=>'cursor: auto') ) !!}
			      	@endif
			    </div>
			    
			    @if(Session::get('userGroup') == 'marketing' OR Session::get('userGroup') == 'clubleitung' OR Session::get('userGroup') == 'admin')
				    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
				     	<label for="evnt_type" class="col-md-2 col-sm-2 col-xs-2">Typ:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            {!! Form::radio('evnt_type', "0", array("checked")) !!}
							{{ trans('mainLang.normalProgramm') }}
				            <br>
				            {!! Form::radio('evnt_type', "2", $type == 2 ? array("checked") : "") !!}
							{{ trans('mainLang.special') }}
				            <br>
				            {!! Form::radio('evnt_type', "3", $type == 3 ? array("checked") : "") !!}
							{{ trans('mainLang.LiveBandDJ') }}
				            <br>
				            {!! Form::radio('evnt_type', "5", $type == 5 ? array("checked") : "") !!}
							{{ trans('mainLang.utilization') }}
				            <br>
				            {!! Form::radio('evnt_type', "4", $type == 4 ? array("checked") : "") !!}
							{{ trans('mainLang.internalEvent') }}
				            <br>
				            {!! Form::radio('evnt_type', "6", $type == 6 ? array("checked") : "") !!}
							{{ trans('mainLang.flooding') }}
				            <br>
				            {!! Form::radio('evnt_type', "7", $type == 7 ? array("checked") : "") !!}
							{{ trans('mainLang.flyersPlacard') }}
				            <br>
				            {!! Form::radio('evnt_type', "8", $type == 8 ? array("checked") : "") !!}
							{{ trans('mainLang.preSale') }}
				            <br>
				            {!! Form::radio('evnt_type', "9", $type == 9 ? array("checked") : "") !!}
							{{ trans('mainLang.others') }}
				            <br>
				            {!! Form::radio('evnt_type', "1", $type == 1 ? array("checked") : "") !!}
							{{ trans('mainLang.information') }}
				            <br>
				            <div>
								{!! Form::checkbox('isPrivate', '1', $private ? false : true) !!}
								{{ trans('mainLang.showExtern') }}
							</div>
				            <br>
							
						</div>
				    </div>
				@else
					<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
				     	<label for="evnt_type" class="control-label col-md-2 col-sm-2 col-xs-2">Typ:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.normalProgramm') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.special') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.LiveBandDJ') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.utilization') }}
				            <br>
				            {!! Form::radio('evnt_type', "4", array("checked")) !!}
							{{ trans('mainLang.internalEvent') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.flooding') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.flyersPlacard') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.preSale') }}
				            <br>
				            {!! Form::radio('evnt_type', "9", $type == 9 ? array("checked") : "") !!}
							{{ trans('mainLang.others') }}
				            <br>
				            {!! Form::radio('evnt_type', "1", $type == 1 ? array("checked") : "") !!}
							{{ trans('mainLang.information') }}
				            <br>
							&nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.survey') }}
							<br>

				            <br>
				            <div>
				            	{!! Form::checkbox('isPrivate', '1', false, array('hidden')) !!}
								<span style="color: red;">
									<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
									{{ trans('mainLang.showForLoggedInMember') }}<br>
									{{ trans('mainLang.showForExternOrChangeType') }} <br>
									{{ trans('mainLang.askTheCLOrMM') }}
								</span>
				            </div>
						</div>
				    </div>					
				@endif

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="place" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.section') }}: &nbsp;</label>
					<span class="col-md-10 col-sm-10 col-xs-12">	   	
						@if($place == "1")
							{!! Form::text('place', 'bc-Club', array('id'=>'place', 'readonly') ) !!}
						@elseif($place == "2")
							{!! Form::text('place', 'bc-Café', array('id'=>'place', 'readonly') ) !!}
						@else
							{{-- Set default values to the club the user is a member in. --}}
							@if(Session::get('userClub') == 'bc-Club')
								{!! Form::text('place', 'bc-Club', array('id'=>'place', 'readonly')) !!}
							@elseif(Session::get('userClub') == 'bc-Café')
								{!! Form::text('place', 'bc-Café', array('id'=>'place', 'readonly') ) !!}
							@endif 
						@endif
					 	<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
					        <span class="caret"></span>
					    </a>
					    <ul class="dropdown-menu">
						    @foreach($places as $place)
						        <li> 
						        	<a href="javascript:void(0);" 
						        	   onClick="document.getElementById('place').value='{{$place}}'">{{ $place }}</a>
						        </li>
							@endforeach
					    </ul>  	
				    </span>
			   	</div>

			   	<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding" id="filter-checkboxes">
					<label for="filter" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.showFor') }}: &nbsp;</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						@if(is_null($filter) OR $filter == "")
							{{-- Set default values to the club the user is a member in.--}}
							@if(Session::get('userClub') == 'bc-Club')
								<div id="filter">
									{!! Form::checkbox('filterShowToClub2', '1', true) !!}
										bc-Club
									&nbsp;&nbsp;&nbsp;&nbsp;
									{!! Form::checkbox('filterShowToClub3', '1', false) !!}
										bc-Café
								</div>
							@elseif(Session::get('userClub') == 'bc-Café')
								<div id="filter">
									{!! Form::checkbox('filterShowToClub2', '1', false) !!}
										bc-Club
									&nbsp;&nbsp;&nbsp;&nbsp;
									{!! Form::checkbox('filterShowToClub3', '1', true) !!}
										bc-Café
								</div>
							@endif 	  
						@else
							<div id="filter">
								{!! Form::checkbox('filterShowToClub2', '1', 
										in_array( "bc-Club", json_decode($filter) ) ? true : false ) !!}
									bc-Club
								&nbsp;&nbsp;&nbsp;&nbsp;
								{!! Form::checkbox('filterShowToClub3', '1', 
										in_array("bc-Café", json_decode($filter)) ? true : false ) !!}
									bc-Café
							</div>	 	
						@endif
				    </div>
			   	</div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="preparationTime" class="control-label col-md-2 col-sm-2 col-xs-4">{{ trans('mainLang.DV-Time') }}:</label>
					<div class="col-md-3 col-sm-3 col-xs-3">
						@if(is_null($dv))
							{{-- Set default values to the club the user is a member in.
							 	 This saves time retyping event start/end times, etc. --}}
							@if(Session::get('userClub') == 'bc-Club')
								<span class="hidden-xs">&nbsp;&nbsp;</span>
								{!! Form::input('time', 'preparationTime', '20:00' ) !!}
							@elseif(Session::get('userClub') == 'bc-Café')
								<span class="hidden-xs">&nbsp;&nbsp;</span>
								{!! Form::input('time', 'preparationTime', '10:45' ) !!}
							@endif
						@else
							<span class="hidden-xs">&nbsp;&nbsp;</span>
							{!! Form::input('time', 'preparationTime', $dv ) !!}
						@endif
					</div>
			    </div>
			    
			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="beginDate" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.begin') }}:</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						@if(is_null($timeStart))
							{{-- Set default values to the club the user is a member in.
							 	 This saves time retyping event start/end times, etc. --}}
							@if(Session::get('userClub') == 'bc-Club')
								{!! Form::input('date', 'beginDate', date("Y-m-d", strtotime($date))) !!}
								{{ trans('mainLang.um') }} {!! Form::input('time', 'beginTime', '21:00') !!}
							@elseif(Session::get('userClub') == 'bc-Café')
								{!! Form::input('date', 'beginDate', date("Y-m-d", strtotime($date))) !!}
								{{ trans('mainLang.um') }} {!! Form::input('time', 'beginTime', '12:00') !!}
							@endif
						@else
							{!! Form::input('date', 'beginDate', date("Y-m-d", strtotime($date))) !!}
							{{ trans('mainLang.um') }} {!! Form::input('time', 'beginTime', $timeStart) !!}
						@endif
					</div>
			    </div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="endDate" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.end') }}:</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						@if(is_null($timeEnd))
							{{-- Set default values to the club the user is a member in.
							 	 This saves time retyping event start/end times, etc. --}}
							@if(Session::get('userClub') == 'bc-Club')
								{!! Form::input('date', 'endDate', date("Y-m-d", strtotime("+1 day", strtotime($date)))) !!}
								{{ trans('mainLang.um') }} {!! Form::input('time', 'endTime', '01:00') !!}
							@elseif(Session::get('userClub') == 'bc-Café')
								{!! Form::input('date', 'endDate', date("Y-m-d", strtotime($date))) !!}
								{{ trans('mainLang.um') }} {!! Form::input('time', 'endTime', '17:00') !!}
							@endif
						@else
							{!! Form::input('date', 'endDate', date("Y-m-d", strtotime($date))) !!}
							{{ trans('mainLang.um') }} {!! Form::input('time', 'endTime', $timeEnd) !!}
						@endif
					</div>
			    </div>

			    <div class="col-md-12 col-sm-12 col-xs-12">
			    	&nbsp;
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label for="password" class="control-label col-md-5 col-sm-5 col-xs-12">{{ trans('mainLang.passwordEntry') }}:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('password', [''] ) !!}
			    	</div>
			    </div>

			    <div class="form-groupcol-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label fro="passwordDouble" class="control-label col-md-5 col-sm-5 col-xs-12">{{ trans('mainLang.passwordRepeat') }}:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('passwordDouble', ['']) !!}
			    	</div>
			    </div>

			    <div class="col-md-12 col-sm-12 col-xs-12">
			    	&nbsp;
			    </div>

		    </div>	
		</div>

		<div class="container col-xs-12 col-sm-12 col-md-6 no-padding-xs">
			<br class="visible-xs visible-sm">
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">{{ trans('mainLang.moreInfos') }}:</h4>({{ trans('mainLang.public') }})
				</div>
				<div class="panel-body">				
				    <div class="form-group">	
						<div class="col-md-12">
							@if(is_null($info))
								{!! Form::textarea('publicInfo', '', array('class'=>'form-control', 
																		  'rows'=>'8',
																		  'placeholder'=>Lang::get('mainLang.placeholderPublicInfo')) ) !!}
							@else
								{!! Form::textarea('publicInfo', $info, array('class'=>'form-control', 
																	  'rows'=>'8',
																	  'placeholder'=>Lang::get('mainLang.placeholderPublicInfo')) ) !!}
							@endif
						</div>
					</div>	
				</div>
			</div>
			<br>
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">{{ trans('mainLang.details') }}:</h4>({{ trans('mainLang.showOnlyIntern') }})
				</div>
				<div class="panel-body">
				    <div class="form-group">
						<div class="col-md-12">
							@if(is_null($details))
								{!! Form::textarea('privateDetails', '', array('class'=>'form-control', 
																			  'rows'=>'5', 
																			  'placeholder'=>Lang::get('mainLang.placeholderPrivateDetails')) ) !!}
							@else
								{!! Form::textarea('privateDetails', $details, array('class'=>'form-control', 
																		  'rows'=>'5', 
																		  'placeholder'=>Lang::get('mainLang.placeholderPrivateDetails')) ) !!}
							@endif
						</div>
				    </div>
				</div>
			</div>
		</div>
	</div>

	<br>
	@include('partials.editSchedule')
	<br>
	
	{!! Form::submit('Veranstaltung mit Dienstplan erstellen', array('class'=>'hidden', 'id'=>'button-create-submit')) !!}
    <input class="hidden" name="evntIsPublished" type="text" value="0" />
    @if(Session::get('userGroup') == 'marketing' OR Session::get('userGroup') == 'clubleitung'  OR Session::get('userGroup') == 'admin')
	<button class="btn btn-primary" id="createAndPublishBtn">{{trans('mainLang.createAndPublish')}}</button>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<br class="visible-xs">
    @endif
	<button class="btn btn-primary" id="createUnpublishedBtn">{{trans('mainLang.createUnpublished')}}</button>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<br class="visible-xs">
	<a href="javascript:history.back()" class="btn btn-default">{{ trans('mainLang.backWithoutChange') }}</a>

	{!! Form::close() !!}
	
@else
	@include('partials.accessDenied')
@endif
@stop



