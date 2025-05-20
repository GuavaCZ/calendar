<?php

namespace Guava\Calendar\Concerns;

trait HandlesDatesSet
{
    protected bool $datesSetEnabled = false;

    public function onDatesSet(array $info = []): void {}

    public function datesSetEnabled(bool $enabled = true): static
    {
        $this->datesSetEnabled = $enabled;

        return $this;
    }

    public function isDatesSetEnabled(): bool
    {
        return $this->datesSetEnabled;
    }
}
