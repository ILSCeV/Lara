<?php

namespace Lara\EventApi;

class Event
{
    public string $import_id;
    public string $name;
    public string $start;
    public \DateTime $start_time;
    public string $end;
    public \DateTime $end_time;
    public ?string $place;
    public ?string $icon;
    public ?string $color;
    public ?int $preparation_time;
    public ?string $marquee;
    public ?string $link;
    public bool $cancelled;
    public \DateTime $updated_on;
}
