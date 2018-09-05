<a href="{{action('IcalController@singleEvent', $clubEvent->id)}}"
   data-toggle="tooltip"
   data-placement="top"
   title="{{ trans('mainLang.addToCalendar')}}">
    @if( $clubEvent->evnt_type == 0)
        <i class="fa fa-calendar-o text"></i>
    @elseif( $clubEvent->evnt_type == 1)
        <small>&nbsp;</small><i class="fa fa-info text"></i><small>&nbsp;</small>
    @elseif( $clubEvent->evnt_type == 2)
        <i class="fa fa-star"></i>
    @elseif( $clubEvent->evnt_type == 3)
        <i class="fa fa-music text"></i>
    @elseif( $clubEvent->evnt_type == 4)
        <i class="fa fa-eye-slash text"></i>
    @elseif( $clubEvent->evnt_type == 5)
        <small>&nbsp;</small><i class="fa fa-eur text"></i><small>&nbsp;</small>
    @elseif( $clubEvent->evnt_type == 6)
        <i class="fa fa-life-ring text"></i>
    @elseif( $clubEvent->evnt_type == 7)
        <i class="fa fa-building text"></i>
    @elseif( $clubEvent->evnt_type == 8)
        <i class="fa fa-ticket text"></i>
    @elseif( $clubEvent->evnt_type == 9)
        <i class="fa fa-list-alt text"></i>
    @elseif( $clubEvent->evnt_type == 10)
        <i class="fa fa-tree text"></i>
    @elseif( $clubEvent->evnt_type == 11)
        <i class="fa fa-cutlery text"></i>
    @endif
</a>
