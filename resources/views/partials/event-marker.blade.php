@if( $clubEvent->evnt_type == 0)
    <?php
    $content = '<i class="fa fa-calendar-o white-text"></i>'
    ?>
@elseif( $clubEvent->evnt_type == 1)
    <?php
    $content = '<small>&nbsp;</small><i class="fa fa-info white-text"></i><small>&nbsp;</small>'
    ?>
@elseif( $clubEvent->evnt_type == 2)
    <?php
    $content = '<i class="fa fa-star white-text"></i>'
    ?>
@elseif( $clubEvent->evnt_type == 3)
    <?php
    $content = '<i class="fa fa-music white-text"></i>'
    ?>
@elseif( $clubEvent->evnt_type == 4)
    <?php
    $content = '<i class="fa fa-eye-slash white-text"></i>'
    ?>
@elseif( $clubEvent->evnt_type == 5)
    <?php
    $content = '<small>&nbsp;</small><i class="fa fa-eur white-text"></i><small>&nbsp;</small>'
    ?>
@elseif( $clubEvent->evnt_type == 6)
    <?php
    $content = '<i class="fa fa-life-ring white-text"></i>'
    ?>
@elseif( $clubEvent->evnt_type == 7)
    <?php
    $content = '<i class="fa fa-building white-text"></i>'
    ?>
@elseif( $clubEvent->evnt_type == 8)
    <?php
    $content = '<i class="fa fa-ticket white-text"></i>'
    ?>
@elseif( $clubEvent->evnt_type == 9)
    <?php
    $content = '<i class="fa fa-list-alt white-text"></i>'
    ?>
@endif
@if($clubEvent->evnt_is_published==1)
    <?php
    $content = '<i class="fa fa-check-square-o" aria-hidden="true"></i><small>&nbsp;</small>'.$content;
    ?>
    @else
    <?php
    $content = '<i class="fa fa-square-o" aria-hidden="true"></i><small>&nbsp;</small>'.$content;
    ?>
@endif
@include("partials.calendarLinkEvent", [$clubEvent, $content])
