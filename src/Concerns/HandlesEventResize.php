<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\ValueObjects\EventResizeInfo;

trait HandlesEventResize
{
    protected bool $eventResizeEnabled = false;

    public function onEventResize(EventResizeInfo $info): bool
    {
        return true;
    }

    public function isEventResizeEnabled(): bool
    {
        return $this->eventResizeEnabled;
    }

    /**
     * @internal Do not override, internal purpose only. Use `onEventResize()` instead
     */
    public function onEventResizeJs(array $info): bool
    {
        // Check if event resize is enabled
        if (! $this->isEventResizeEnabled()) {
            return false;
        }

        $this->setMountedActionContextData($info);
        $this->resolveEventRecord();

        return $this->onEventResize(new EventResizeInfo($info, $this->getEventRecord(), $this->shouldUseFilamentTimezone()));
    }
}
