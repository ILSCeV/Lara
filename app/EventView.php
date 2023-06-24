<?php

namespace Lara;

use Illuminate\Support\Carbon;
use Lara\EventApi\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsStringable;

/** EVENT_VIEW
 * @property string import_id
 * @property string name
 * @property Carbon\Carbon start
 * @property string start_time
 * @property Carbon\Carbon end
 * @property string end_time
 * @property string place
 * @property string marquee
 * @property string link
 * @property bool cancelled
 * @property Carbon\Carbon updated_on
 * @property int section_id
 */
class EventView extends Model
{
    use HasFactory;

    protected $table = 'EVENT_VIEW';

    protected $fillable = ['import_id', 'name', 'start', 'start_time', 'end', 'end_time', 'place', 'marquee', 'link', 'cancelled', 'updated_on', 'section_id', 'icon'];

    protected $casts = ['start' => 'date', 'start_time' => 'datetime', 'end' => 'date', 'end_time' => 'datetime', 'updated_on' => 'datetime'];

    public function toEvent(): Event
    {
        $this->setEmptyStringToNull();

        $event = new Event();

        // Checking if $this->start and $this->end are not null before calling the toDateString() method
        $event->start = $this->start ? $this->start->toDateString() : null;
        $event->start_time = $this->start_time ? $this->start_time->toTimeString() : null;
        $event->end = $this->end ? $this->end->toDateString() : null;
        $event->end_time = $this->end_time ? $this->end_time->toTimeString() : null;

        $event->import_id = $this->import_id;
        $event->name = $this->name;
        $event->place = $this->place;
        $event->icon = $this->icon;
        $event->color = $this->color;
        $event->preparation_time = $this->preparation_time;
        $event->marquee = $this->marquee;
        $event->link = $this->link;

        // Using the null coalescing operator to set the value of $event->cancelled
        $event->cancelled = $this->cancelled ?? false;

        // Checking if $this->updated_on is not null before calling the Carbon::create() and toIso8601ZuluString() methods
        $event->updated_on = Carbon::create($this->updated_on)->toIso8601ZuluString();

        return $event;
    }


    private function setEmptyStringToNull()
    {
        $columns = $this->getFillable();
        $attributes = $this->getAttributes();
        foreach ($columns as $column) {
            if (array_key_exists($column, $attributes) && empty($attributes[$column])) {
                $this->attributes[$column] = null;
            }
        }
    }
}
