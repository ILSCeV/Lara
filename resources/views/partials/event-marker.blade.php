<!-- Event marker -->
@if( $clubEvent->evnt_type == 0)
	<span class="marker-0"><i class="fa fa-calendar-o"></i></span>
@elseif( $clubEvent->evnt_type == 1)
	<span class="marker-1"><small>&nbsp;</small><i class="fa fa-info"></i><small>&nbsp;</small></span>
@elseif( $clubEvent->evnt_type == 2)
	<span class="marker-2"><i class="fa fa-star"></i></span>
@elseif( $clubEvent->evnt_type == 3)
	<span class="marker-3"><i class="fa fa-music"></i></span>
@elseif( $clubEvent->evnt_type == 4)
	<span class="marker-4"><i class="fa fa-eye-slash"></i></span>
@elseif( $clubEvent->evnt_type == 5)
	<span class="marker-5"><small>&nbsp;</small><i class="fa fa-eur"></i><small>&nbsp;</small></span>
@endif
