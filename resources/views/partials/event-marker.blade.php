<a href="{{action('IcalController@singleEvent', $clubEvent->id)}}"
   data-bs-toggle="tooltip"
   data-bs-placement="top"
   class="{{\Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent)}}"
   title="{{ __('mainLang.addToCalendar')}}">
    @if( $clubEvent->evnt_type == 0)
        <i class="fa-solid  fa-calendar-alt text"></i>
    @elseif( $clubEvent->evnt_type == 1)
        <small>&nbsp;</small><i class="fa-solid  fa-info text"></i><small>&nbsp;</small>
    @elseif( $clubEvent->evnt_type == 2)
        <i class="fa-solid  fa-star"></i>
    @elseif( $clubEvent->evnt_type == 3)
        <i class="fa-solid  fa-music text"></i>
    @elseif( $clubEvent->evnt_type == 4)
        <i class="fa-solid  fa-eye-slash text"></i>
    @elseif( $clubEvent->evnt_type == 5)
        <small>&nbsp;</small><i class="fa-solid  fa-money-bill-alt text"></i><small>&nbsp;</small>
    @elseif( $clubEvent->evnt_type == 6)
        <i class="fa-solid  fa-life-ring text"></i>
    @elseif( $clubEvent->evnt_type == 7)
        <i class="fa-solid  fa-building text"></i>
    @elseif( $clubEvent->evnt_type == 8)
        <i class="fa-solid  fa-ticket-alt text"></i>
    @elseif( $clubEvent->evnt_type == 9)
        <i class="fa-solid  fa-list-alt text"></i>
    @elseif( $clubEvent->evnt_type == 10)
        <i class="fa-solid  fa-tree text"></i>
    @elseif( $clubEvent->evnt_type == 11)
        <i class="fa-solid  fa-utensils text"></i>
    @endif
</a>
