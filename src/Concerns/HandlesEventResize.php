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
    protected function onEventResize(EventResizeInfo $info, Model $event): bool
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
        // The global flag is authoritative: if resizing is disabled, nothing is resizable.
        if (! $this->isEventResizeEnabled()) {
            return false;
        }

        // Resolving the record also verifies the event signature, which covers the resize-lock flag.
        $this->setRawCalendarContextData(Context::EventResize, $data);

        // The lock flag is now trusted (part of the verified signature), so a client cannot strip a
        // per-event lock to resize an event the developer pinned.
        if ((bool) $this->getRawCalendarContextData('event.extendedProps.resizeLocked')) {
            return false;
        }

        return $this->onEventResize($this->getCalendarContextInfo(), $this->getEventRecord());
    }
}
