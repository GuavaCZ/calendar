<?php

namespace Guava\Calendar\Concerns;

trait HasCalendarView
{
    protected string $calendarView = 'dayGridMonth';

    public function getCalendarView(): string
    {
        return $this->calendarView;
    }
}
