<?php

namespace Lara;

use Lara\EventApi\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** EVENT_VIEW
 * @property string import_id
 * @property string name
 * @property \DateTime start
 * @property string start_time
 * @property \DateTime end
 * @property string end_time
 * @property string place
 * @property string marquee
 * @property string link
 * @property bool cancelled
 * @property \DateTime updated_on
 * @property int section_id
 */
class EventView extends Model
{
    use HasFactory;

    protected $table = 'EVENT_VIEW';

    protected $fillable = ['import_id', 'name', 'start', 'start_time', 'end', 'end_time', 'place', 'marquee', 'link', 'cancelled', 'updated_on', 'section_id'];

    public function toEvent(){
        $event = new Event();
        $event->import_id = $this->import_id;
        $event->name = $this->name;
        $event->start = $this->start;
        $event->start_time = $this->start_time;
        $event->end = $this->end;
        $event->end_time = $this->end_time;
        $event->place = $this->place;
        $event->icon = $this->icon;
        $event->color = $this->color;
        $event->preparation_time = $this->preparation_time;
        $event->marquee = $this->marquee;
        $event->link = $this->link;
        $event->cancelled = $this->cancelled;
        $event->updated_on = new \DateTime($this->updated_on);
        return $event;
    }
}
