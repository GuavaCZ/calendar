<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\ValueObjects\EventAllUpdatedInfo;
use Guava\Calendar\ValueObjects\EventDropInfo;

trait HandlesEventDragAndDrop
{
    protected bool $eventDragEnabled = false;

    protected function onEventDrop(EventDropInfo $info): bool
    {
        return true;
    }

    public function isEventDragEnabled(): bool
    {
        return $this->eventDragEnabled;
    }

    /**
     * @internal Do not override, internal purpose only. Use `onEventDrop()` instead
     */
    public function onEventDropJs(array $info): bool
    {
        // Check if event drag and drop is enabled
        if (! $this->isEventDragEnabled()) {
            return false;
        }

        $this->setMountedActionContextData($info);
        $this->resolveEventRecord();

        return $this->onEventDrop(new EventDropInfo($info, $this->getEventRecord(), $this->shouldUseFilamentTimezone()));
    }
}
