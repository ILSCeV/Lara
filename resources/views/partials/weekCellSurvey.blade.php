&nbsp;
@if(!Session::has('userId') AND $survey->is_private == 0)
	{{-- check current session for user role && and the survey for private status--}}
    <div class="panel panel-warning">

		{{--if so show a grey placeholder for the user--}}
        <div class="panel dark-grey white-text" style="padding: 15px 15px 8px 15px;">
			<h4 class="panel-title">
					<i class="fa fa-bar-chart-o white-text"></i>
					{{--and show him thats a private survey ( =Interne Umfrage in german)--}}
					<span class="name">Interne Umfrage</span>
			</h4>
			<i class="fa fa-times" aria-hidden="true"></i>
			{{ utf8_encode(strftime("%a, %d. %b", strtotime($survey->deadline))) }}
		</div>

		<div class="panel panel-body no-padding">
            </div>
	</div>
@else
	{{----}}
	<div class="panel panel-warning">
	<div class="panel panel-heading calendar-public-info white-text">
			<h4 class="panel-title">
				<a href="{{ URL::route('survey.show', $survey->id) }}">
					<i class="fa fa-bar-chart-o white-text"></i>&nbsp;
					<span class="name">{{ $survey->title }}</span>
				</a>
			</h4>

		<i class="fa fa-times" aria-hidden="true"></i>
			{{ utf8_encode(strftime("%a, %d. %b", strtotime($survey->deadline))) }}
	</div>
	<div class="panel panel-body no-padding">


@if(Session::has('userGroup')
               AND (Session::get('userGroup') == 'marketing'
               OR Session::get('userGroup') == 'clubleitung'
               OR Session::get('userGroup') == 'admin'))
	<hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">
	<div class="padding-right-16 bottom-padding pull-right hidden-print">
		<small><a href="#" class="hide-event">Ausblenden</a></small>
	</div>
	</div>
@endif

@endif
</div>
