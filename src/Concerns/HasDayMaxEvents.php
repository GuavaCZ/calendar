<?php

namespace Guava\Calendar\Concerns;

use Carbon\WeekDay;

trait HasDayMaxEvents
{
    protected bool $dayMaxEvents = false;

    public function dayMaxEvents(bool $dayMaxEvents = true): static
    {
        $this->dayMaxEvents = $dayMaxEvents;

        return $this;
    }

    public function getDayMaxEvents(): bool
    {
        return $this->evaluate($this->dayMaxEvents);
    }
}
