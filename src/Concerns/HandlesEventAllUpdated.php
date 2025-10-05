<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\ValueObjects\EventAllUpdatedInfo;

trait HandlesEventAllUpdated
{
    protected bool $eventAllUpdatedEnabled = false;

    protected function onEventAllUpdated(EventAllUpdatedInfo $info): void {}

    public function isEventAllUpdatedEnabled(): bool
    {
        return $this->eventAllUpdatedEnabled;
    }

    /**
     * @internal Do not override, internal purpose only. Use `onEventAllUpdated()` instead
     */
    public function onEventAllUpdatedJs(array $info): void
    {
        // Check if dates set is enabled
        if (! $this->isEventAllUpdatedEnabled()) {
            return;
        }

        $this->onEventAllUpdated(new EventAllUpdatedInfo($info, $this->shouldUseFilamentTimezone()));
    }
}
