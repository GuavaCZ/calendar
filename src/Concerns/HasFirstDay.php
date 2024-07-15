<?php

namespace Guava\Calendar\Concerns;

trait HasFirstDay
{
    protected int $firstDay = 1;

    public function firstDay(int $firstDay): static
    {
        $this->firstDay = $firstDay;

        return $this;
    }

    public function getFirstDay(): int
    {
        return $this->evaluate($this->firstDay);
    }
}
