<a href="{{action('IcalController@singleEvent', $clubEvent->id)}}"
   data-toggle="tooltip" 
   data-placement="top"
   title="{{ trans('mainLang.addToCalendar')}}">
   	{!! $content !!}
</a>

