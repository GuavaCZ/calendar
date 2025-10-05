<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\EventResizeInfo;
use Illuminate\Database\Eloquent\Model;

trait HandlesEventResize
{
    protected bool $eventResizeEnabled = false;

    // TODO: Add a default implementation
    // TODO: for that we need to add two methods to Eventable interface:
    // TODO: -> getStartAttribute()
    // TODO: -> getEndAttribute()
    // TODO: where the user needs to define which attributes is the start/end date
    // TODO: Then we can handle the update outselves by default
    public function onEventResize(EventResizeInfo $info, Model $event): bool
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
    public function onEventResizeJs(array $data): bool
    {
        // Check if event resize is enabled
        if (! $this->isEventResizeEnabled()) {
            return false;
        }

        $this->setRawCalendarContextData(Context::EventResize, $data);

        return $this->onEventResize($this->getCalendarContextInfo(), $this->getEventRecord());
    }
}
