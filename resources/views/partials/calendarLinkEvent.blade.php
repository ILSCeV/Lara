<a href="{{action('IcalController@singleEvent', $clubEvent->id)}}"
   data-bs-toggle="tooltip" 
   data-bs-placement="top"
   title="{{ __('mainLang.addToCalendar')}}">
   	{!! $content !!}
</a>

