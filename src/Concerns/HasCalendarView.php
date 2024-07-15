<?php

namespace Guava\Calendar\Concerns;

trait HasCalendarView
{
    protected string $calendarView = 'dayGridMonth';

    public function calendarView(string $calendarView): static
    {
        $this->calendarView = $calendarView;

        return $this;
    }

    public function getCalendarView(): string
    {
        return $this->evaluate($this->calendarView);
    }
}
