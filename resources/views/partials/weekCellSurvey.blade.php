@if(!Session::has('userId') AND $survey->is_private === 1)
    {{-- Hide internal surveys from guests --}}
    <div class="panel panel-warning">

        <div class="panel palette-Grey-500 bg white-text" 
             style="padding: 15px 15px 8px 15px;">
            <h4 class="panel-title">
                <i class="fa fa-bar-chart-o white-text"></i>
                &nbsp;
                <span class="name">{{ trans('mainLang.internalSurvey') }}</span>
            </h4>
            <i class="fa fa-times" aria-hidden="true"></i>
            {{ utf8_encode(strftime("%a, %d. %b", strtotime($survey->deadline))) }}
        </div>

        <div class="panel panel-body no-padding">
        </div>
    </div>
@else 
    {{-- Show everything to memebers --}}
    <div class="panel panel-warning">

        <div class="panel panel-heading palette-Purple-900 bg white-text">
            <h4 class="panel-title">
                <a href="{{ URL::route('survey.show', $survey->id) }}">
                    <i class="fa fa-bar-chart-o white-text"></i>
                    &nbsp;
                    <span class="name">{{ $survey->title }}</span>
                </a>
            </h4>
            <i class="fa fa-times" aria-hidden="true"></i>
            {{ utf8_encode(strftime("%a, %d. %b", strtotime($survey->deadline))) }}
        </div>

        <div class="panel panel-body no-padding">
            {{-- gives a session from privileged users the option to hide the event--}}
            @if (Session::has('userGroup')
            AND (Session::get('userGroup') == 'marketing'
            OR   Session::get('userGroup') == 'clubleitung'
            OR   Session::get('userGroup') == 'admin'))

                <hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">
                <div class="padding-right-16 bottom-padding pull-right hidden-print">
                    <small><a href="#" class="hide-event">{{ trans('mainLang.hide') }}</a></small>
                </div>
                
            @endif
        </div>
    </div>
@endif
