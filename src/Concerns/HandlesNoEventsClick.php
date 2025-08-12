<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\ValueObjects\NoEventsClickInfo;

trait HandlesNoEventsClick
{
    protected bool $noEventsClickEnabled = false;

    protected function onNoEventsClick(NoEventsClickInfo $info): void {}

    public function isNoEventsClickEnabled(): bool
    {
        return $this->noEventsClickEnabled;
    }

    /**
     * @internal Do not override, internal purpose only. Use `onDateClick` instead
     */
    public function onNoEventsClickJs(array $info): void
    {
        // Check if no events click is enabled
        if (! $this->isNoEventsClickEnabled()) {
            return;
        }

        $this->onNoEventsClick(new NoEventsClickInfo($info, $this->shouldUseFilamentTimezone()));
    }
}
