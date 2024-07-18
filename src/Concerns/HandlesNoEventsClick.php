<?php

namespace Guava\Calendar\Concerns;

trait HandlesNoEventsClick
{
    protected bool $noEventsClickEnabled = false;

    public function onNoEventsClick($info): void {}

    public function noEventsClickEnabled(bool $enabled = true): static
    {
        $this->noEventsClickEnabled = $enabled;

        return $this;
    }

    public function isNoEventsClickEnabled(): bool
    {
        return $this->noEventsClickEnabled;
    }
}
