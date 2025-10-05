<?php

namespace Guava\Calendar\Contracts;

use Guava\Calendar\ValueObjects\CalendarResource;

interface Resourceable
{
    public function toCalendarResource(): CalendarResource;
}
