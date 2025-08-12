<?php

namespace Guava\Calendar\Contracts;

use Guava\Calendar\ValueObjects\CalendarEvent;

interface Eventable
{
    public function toCalendarEvent(): CalendarEvent;
}
