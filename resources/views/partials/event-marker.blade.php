<a href="{{action('IcalController@singleEvent', $clubEvent->id)}}"
   data-toggle="tooltip"
   data-placement="top"
   class="text-white"
   title="{{ trans('mainLang.addToCalendar')}}">
    @if( $clubEvent->evnt_type == 0)
        <i class="fas fa-calendar-alt text"></i>
    @elseif( $clubEvent->evnt_type == 1)
        <small>&nbsp;</small><i class="fas fa-info text"></i><small>&nbsp;</small>
    @elseif( $clubEvent->evnt_type == 2)
        <i class="fas fa-star"></i>
    @elseif( $clubEvent->evnt_type == 3)
        <i class="fas fa-music text"></i>
    @elseif( $clubEvent->evnt_type == 4)
        <i class="fas fa-eye-slash text"></i>
    @elseif( $clubEvent->evnt_type == 5)
        <small>&nbsp;</small><i class="fas fa-eur text"></i><small>&nbsp;</small>
    @elseif( $clubEvent->evnt_type == 6)
        <i class="fas fa-life-ring text"></i>
    @elseif( $clubEvent->evnt_type == 7)
        <i class="fas fa-building text"></i>
    @elseif( $clubEvent->evnt_type == 8)
        <i class="fas fa-ticket-alt text"></i>
    @elseif( $clubEvent->evnt_type == 9)
        <i class="fas fa-list-alt text"></i>
    @elseif( $clubEvent->evnt_type == 10)
        <i class="fas fa-tree text"></i>
    @elseif( $clubEvent->evnt_type == 11)
        <i class="fas fa-cutlery text"></i>
    @endif
</a>
