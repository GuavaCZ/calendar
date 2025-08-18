<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Enums\CalendarViewType;

trait HasCalendarView
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    public function getCalendarView(): CalendarViewType
    {
        return $this->calendarView;
    }
}
