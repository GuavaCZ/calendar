<?php

namespace Guava\Calendar\Concerns;

trait HandlesDateClick
{
    protected bool $dateClickEnabled = false;

    public function onDateClick(array $info = []): void {}

    public function dateClickEnabled(bool $enabled = true): static
    {
        $this->dateClickEnabled = $enabled;

        return $this;
    }

    public function isDateClickEnabled(): bool
    {
        return $this->dateClickEnabled;
    }
}
