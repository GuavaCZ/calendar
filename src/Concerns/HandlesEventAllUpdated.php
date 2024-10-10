<?php

namespace Guava\Calendar\Concerns;

trait HandlesEventAllUpdated
{
    protected bool $eventAllUpdatedEnabled = false;

    public function onEventAllUpdated(array $info = []): void {}

    public function eventAllUpdated(bool $enabled = true): static
    {
        $this->eventAllUpdatedEnabled = $enabled;

        return $this;
    }

    public function isEventAllUpdatedEnabled(): bool
    {
        return $this->eventAllUpdatedEnabled;
    }
}
