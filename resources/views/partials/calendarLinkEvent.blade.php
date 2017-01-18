<a href="{{true?'http://www.google.com/calendar/event?action=TEMPLATE':'https://calendar.google.com/calendar/gp#~calendar:view=e&bm=1'}}
		&text={{$clubEvent->evnt_title}}
		&dates={{date_format(date_create($clubEvent->evnt_date_start), 'Ymd')}}{{date_format(date_create($clubEvent->evnt_time_start),'\\THi00')}}/{{date_format(date_create($clubEvent->evnt_date_end),'Ymd')}}{{date_format(date_create($clubEvent->evnt_time_end),'\\THi00')}}
@if     ($clubEvent->evnt_public_info != "")
		&details={{$clubEvent->evnt_public_info}}
@endif
		&location=Max-Planck-Ring+16,+98693+Ilmenau
		&ctz=Europe/Berlin
		&trp=false" 
   target="_blank" 
   rel="nofollow" 
   data-toggle="tooltip" 
   data-placement="left"
   title="{{ trans('mainLang.addToCalendar')}}">{!! $content !!}</a>
