<?php
/**
 * Utility class to provide some functions needed in the blade files
 *
 * User: fabian
 * Date: 08.10.18
 * Time: 19:13
 */

namespace Lara\utilities;


use Lara\ClubEvent;

class ViewUtility
{
    
    static function getEventPaletteClass(ClubEvent $clubEvent)
    {
        $clubEventClass = "palette-".$clubEvent->section->color;
        switch ($clubEvent->evnt_type) {
            case 0:
                $clubEventClass .= "-700 bg";
                break;
            case 1:
                $clubEventClass .= " palette-Purple-500 bg";
                break;
            case 2:
            case 3:
            case 10:
            case 11:
                $clubEventClass .= "-900 bg";
                break;
            case 4:
            case 5:
            case 6:
            case 9:
                $clubEventClass .= "-500 bg";
                break;
            case 7:
            case 8:
                $clubEventClass .= "-300 bg";
                break;
            default:
                $clubEventClass .= "-500 bg";
        }
        
        return $clubEventClass;
    }
    
    public static function isLightMode() {
        return \Session::get('view_mode','light') == 'light';
    }
}
