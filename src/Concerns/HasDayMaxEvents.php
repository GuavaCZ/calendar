<?php

namespace Guava\Calendar\Concerns;

trait HasDayMaxEvents
{
    /**
     * Determines the maximum number of stacked event levels for a given day in the dayGrid view.
     *
     * If there are too many events, a link like +2 more is displayed.
     *
     * Currently, only a boolean value is supported. When set to true, it limits the number of events to the height of the day cell. When set to false (default) there is no limit.
     */
    protected bool $dayMaxEvents = false;

    /**
     * Determines the maximum number of stacked event levels for a given day in the dayGrid view.
     *
     * If there are too many events, a link like +2 more is displayed.
     *
     * Currently, only a boolean value is supported. When set to true, it limits the number of events to the height of the day cell. When set to false (default) there is no limit.
     */
    public function getDayMaxEvents(): bool
    {
        return $this->dayMaxEvents;
    }
}
