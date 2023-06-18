<?php

namespace Lara\EventApi;

use Carbon\Carbon;

class Event
{
    public string $import_id;
    public string $name;
    public string $start;
    public string $start_time;
    public string $end;
    public string $end_time;
    public ?string $place;
    public ?string $icon;
    public ?string $color;
    public ?int $preparation_time;
    public ?string $marquee;
    public ?string $link;
    public bool $cancelled;
    public string $updated_on;
}
