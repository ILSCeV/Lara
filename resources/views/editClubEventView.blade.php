@extends('layouts.master')

@section('title')
	{{ trans('mainLang.changeEventJob') }}
@stop

@section('content')

@if(Session::has('userId')
AND (Session::get('userGroup') == 'marketing'
 OR Session::get('userGroup') == 'clubleitung'
 OR Session::get('userGroup') == 'admin'
 OR Session::get('userId') == $created_by))
	
	{!! Form::open(['method' => 'PUT', 'route' => ['event.update', $event->id]]) !!}

<div class="row" xmlns="http://www.w3.org/1999/html">
		<div class="panel col-md-6 col-sm-12 col-xs-12">

			<div class="panel-heading">
				<h4 class="panel-title">{{ trans('mainLang.changeEventJob') }}:</h4>
			</div>

			<br>

			<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">			
					{!! Form::checkbox('saveAsTemplate', '1', $event->getSchedule->schdl_is_template, array('class'=>'col-md-1 col-sm-1 col-xs-1', 'hidden')) !!}

					@if ($event->getSchedule->schdl_is_template)
						{!! Form::text('templateName', $event->getSchedule->schdl_title, array('id'=>'templateName', 'hidden') ) !!}
						<label for="saveAsTemplate" class="col-md-12 col-sm-12 col-xs-12">
							{!!'(Gespeichert als Vorlage <b>"' . $event->getSchedule->schdl_title . '"</b>)' !!}
						</label>
					@else
						<label for="saveAsTemplate" class="col-md-12 col-sm-12 col-xs-12">
							{!! "(Dieser Event ist nicht als Vorlage gespeichert.)" !!}
						</label>
					@endif

					
			   	</div>

			<br>
			
			<div class="panel-body no-padding">
				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			      	<label for="title" class="col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.title') }}:</label>
		      		{!! Form::text('title', 
		      						$event->evnt_title, 
		      						array('class'=>'col-md-9 col-sm-9 col-xs-8', 
  										  'placeholder'=>Lang::get('mainLang.placeholderTitleWineEvening'),
  										  'style'=>'cursor: auto',
  										  'required') ) !!}
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="subtitle" class="col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.subTitle') }}:</label>
					{!! Form::text('subtitle', 
									$event->evnt_subtitle, 
									array('class'=>'col-md-9 col-sm-9 col-xs-8', 
  										  'placeholder'=>Lang::get('mainLang.placeholderSubTitleWineEvening'),
  										  'style'=>'cursor: auto') ) !!}
			    </div>
			    
			    @if(Session::get('userGroup') == 'marketing' OR Session::get('userGroup') == 'clubleitung'  OR Session::get('userGroup') == 'admin')
				    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
				     	<label for="evnt_type" class="col-md-2 col-sm-2 col-xs-2">{{ trans('mainLang.type') }}:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            {!! Form::radio('evnt_type', "0", $event->evnt_type == 0 ? array("checked") : "") !!}
							{{ trans('mainLang.normalProgramm') }}
				            <br>
				            {!! Form::radio('evnt_type', "2", $event->evnt_type == 2 ? array("checked") : "") !!}
							{{ trans('mainLang.special') }}
				            <br>
				            {!! Form::radio('evnt_type', "3", $event->evnt_type == 3 ? array("checked") : "") !!}
							{{ trans('mainLang.LiveBandDJ') }}
				            <br>
				            {!! Form::radio('evnt_type', "5", $event->evnt_type == 5 ? array("checked") : "") !!}
							{{ trans('mainLang.utilization') }}
				            <br>
				            {!! Form::radio('evnt_type', "4", $event->evnt_type == 4 ? array("checked") : "") !!}
							{{ trans('mainLang.internalEvent') }}
				            <br>
				            {!! Form::radio('evnt_type', "6", $event->evnt_type == 6 ? array("checked") : "") !!}
							{{ trans('mainLang.flooding') }}
				            <br>
				            {!! Form::radio('evnt_type', "7", $event->evnt_type == 7 ? array("checked") : "") !!}
							{{ trans('mainLang.flyersPlacard') }}
				            <br>
				            {!! Form::radio('evnt_type', "8", $event->evnt_type == 8 ? array("checked") : "") !!}
							{{ trans('mainLang.preSale') }}
				            <br>
				            {!! Form::radio('evnt_type', "9", $event->evnt_type == 9 ? array("checked") : "") !!}
							{{ trans('mainLang.others') }}
				            <br>
				            {!! Form::radio('evnt_type', "1", $event->evnt_type == 1 ? array("checked") : "") !!}
							{{ trans('mainLang.information') }}
				            <br>

				            <br>
				            <div>
								{!! Form::checkbox('isPrivate', '1', ($event->evnt_is_private + 1)%2) !!}
								{{ trans('mainLang.showExtern') }}
							</div>
				            <br>
                            <div>
                                <div class="hidden">
                                    {!! Form::checkbox('evntIsPublished', '1', $event->evnt_is_published == 1) !!}
                                </div>
                                <button type="button" class="btn" id="publishBtn"> <i id="eventPublishedIndicator" class="fa @if($event->evnt_is_published == 1) fa-check-square-o @else fa-square-o @endif" aria-hidden="true"></i> {{ trans('mainLang.publishEvent') }}</button>
                            </div>
						</div>
				    </div>
				@else
					<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
				     	<label for="evnt_type" class="control-label col-md-2 col-sm-2 col-xs-2">{{ trans('mainLang.type') }}:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            @if ($event->evnt_type == 0)
				            	{!! Form::radio('evnt_type', "0", $event->evnt_type == 0 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
								{{ trans('mainLang.normalProgramm') }}
				            <br>
				            @if ($event->evnt_type == 2)
				            	{!! Form::radio('evnt_type', "2", $event->evnt_type == 2 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
								{{ trans('mainLang.special') }}
				            <br>
				            @if ($event->evnt_type == 3)
				            	{!! Form::radio('evnt_type', "3", $event->evnt_type == 3 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
								{{ trans('mainLang.LiveBandDJ') }}
				            <br>
				            @if ($event->evnt_type == 5)
				            	{!! Form::radio('evnt_type', "5", $event->evnt_type == 5 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
								{{ trans('mainLang.utilization') }}
				            <br>
				            {!! Form::radio('evnt_type', "4", $event->evnt_type == 4 ? array("checked") : "") !!}
								{{ trans('mainLang.internalEvent') }}
				            <br>
				            @if ($event->evnt_type == 6)
				            	{!! Form::radio('evnt_type', "6", $event->evnt_type == 6 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
								{{ trans('mainLang.flooding') }}
				            <br>
				            @if ($event->evnt_type == 7)
				            	{!! Form::radio('evnt_type', "7", $event->evnt_type == 7 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
								{{ trans('mainLang.flyersPlacard') }}
				            <br>
				            @if ($event->evnt_type == 8)
				            	{!! Form::radio('evnt_type', "8", $event->evnt_type == 8 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
								{{ trans('mainLang.preSale') }}
				            <br>
				            {!! Form::radio('evnt_type', "9", $event->evnt_type == 9 ? array("checked") : "") !!}
								{{ trans('mainLang.others') }}
				            <br>
				            {!! Form::radio('evnt_type', "1", $event->evnt_type == 1 ? array("checked") : "") !!}
								{{ trans('mainLang.information') }}
				            <br>

				            <br>
				            <div>
				            	@if ($event->evnt_is_private == 0)
				            		{!! Form::checkbox('isPrivate', '1', ($event->evnt_is_private + 1)%2) !!}
									{{ trans('mainLang.showExtern') }}
				            	@else
					            	{!! Form::checkbox('isPrivate', '1', ($event->evnt_is_private + 1)%2, array('hidden')) !!}
									<span style="color: red;">
										<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
										{{ trans('mainLang.showForLoggedInMember') }}<br>
										{{ trans('mainLang.showForExternOrChangeType') }} <br>
										{{ trans('mainLang.askTheCLOrMM') }}
									</span>
				            	@endif
				            </div>
						</div>
				    </div>					
				@endif

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="place" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.section') }}: &nbsp;</label>
					<span class="col-md-10 col-sm-10 col-xs-12">
						{!! Form::text('place', $places[$event->plc_id], array('id'=>'place', 'readonly') ) !!}
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
						<div id="filter">
							{!! Form::checkbox('filterShowToClub2', '1', 
									in_array( "bc-Club", json_decode($event->evnt_show_to_club) ) ? true : false ) !!}
								bc-Club
							&nbsp;&nbsp;&nbsp;&nbsp;
							{!! Form::checkbox('filterShowToClub3', '1', 
									in_array("bc-Café", json_decode($event->evnt_show_to_club)) ? true : false ) !!}
								bc-Café
						</div>	   	
				    </div>
			   	</div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="preparationTime" class="control-label col-md-2 col-sm-2 col-xs-4">{{ trans('mainLang.DV-Time') }}:</label>
					<div class="col-md-3 col-sm-3 col-xs-3">
						{!! Form::input('time', 'preparationTime', $event->getSchedule->schdl_time_preparation_start) !!}
					</div>
			    </div>
			    
			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="beginDate" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.begin') }}:</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						{!! Form::input('date', 'beginDate', $event->evnt_date_start) !!} 
						<span class="visible-xs"><br></span>{{ trans('mainLang.um') }} {!! Form::input('time', 'beginTime', $event->evnt_time_start) !!}
					</div>
			    </div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="endDate" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.end') }}:</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						{!! Form::input('date', 'endDate', $event->evnt_date_end) !!} 
						<span class="visible-xs"><br></span>{{ trans('mainLang.um') }} {!! Form::input('time', 'endTime', $event->evnt_time_end) !!}
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

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label fro="passwordDouble" class="control-label col-md-5 col-sm-5 col-xs-12">{{ trans('mainLang.passwordRepeat') }}:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('passwordDouble', ['']) !!}
			    	</div>
			    </div>

			    <div style="color: #ff9800;">
			    	<small>{{ trans('mainLang.passwordDeleteMessage') }} </small>
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
					<h4 class="panel-title">{{ trans('mainLang.moreInfos') }} :</h4>({{ trans('mainLang.public') }} )
				</div>
				<div class="panel-body">				
				    <div class="form-group">	
						<div class="col-md-12">
							{!! Form::textarea('publicInfo', $event->evnt_public_info, array('class'=>'form-control', 
																	  'rows'=>'8',
																	  'placeholder'=>Lang::get('mainLang.placeholderPublicInfo')) ) !!}
						</div>
					</div>	
				</div>
			</div>
			<br>
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">{{ trans('mainLang.details') }} :</h4>({{ trans('mainLang.showOnlyIntern') }} )
				</div>
				<div class="panel-body">
				    <div class="form-group">
						<div class="col-md-12">
							{!! Form::textarea('privateDetails', $event->evnt_private_details, array('class'=>'form-control', 
																		  'rows'=>'5', 
																		  'placeholder'=>Lang::get('mainLang.placeholderPrivateDetails')) ) !!}
						</div>
				    </div>
				</div>
			</div>
		</div>
	</div>

	<br>
	@include('partials.editSchedule')
	<br>

	{!! Form::submit('Änderungen speichern', array('class'=>'btn btn-success', 'id'=>'button-edit-submit')) !!}
	&nbsp;&nbsp;&nbsp;&nbsp;
	<br class="visible-xs"><br class="visible-xs">
	<a href="javascript:history.back()" class="btn btn-default">{{ trans('mainLang.backWithoutChange') }} </a>
	{!! Form::close() !!}

@else

	<div class="panel panel-warning">
		<div class="panel panel-heading">
			<h4 class="white-text">{{ trans('mainLang.noNotThisWay') }}</h4>
		</div>
		<div class="panel panel-body">
			@if ($creator_name == "")
				<h6>{{ trans('mainLang.onlyThe') }} <b>{{ trans('mainLang.clubManagement') }}</b> {{ trans('mainLang.orThe') }} <b>{{ trans('mainLang.marketingManager') }}</b> {{ trans('mainLang.canChangeEventJob') }}</h6>
			@else
				<h6>{{ trans('mainLang.only') }} <b>{!! $creator_name !!}</b>{{ trans('mainLang.commaThe') }} <b>{{ trans('mainLang.clubManagement') }}</b> {{ trans('mainLang.orThe') }} <b>{{ trans('mainLang.marketingManager') }}</b> {{ trans('mainLang.canChangeEventJob') }}</h6>
			@endif
		</div>
	</div>
@endif

@stop




