<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\EventDropInfo;
use Illuminate\Database\Eloquent\Model;

trait HandlesEventDragAndDrop
{
    protected bool $eventDragEnabled = false;

    // TODO: Add a default implementation
    // TODO: for that we need to add two methods to Eventable interface:
    // TODO: -> getStartAttribute()
    // TODO: -> getEndAttribute()
    // TODO: where the user needs to define which attributes is the start/end date
    // TODO: Then we can handle the update outselves by default
    protected function onEventDrop(EventDropInfo $info, Model $event): bool
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
    public function onEventDropJs(array $data): bool
    {
        // The global flag is authoritative: if dragging is disabled, nothing is draggable.
        if (! $this->isEventDragEnabled()) {
            return false;
        }

        // Resolving the record also verifies the event signature, which covers the drag-lock flag.
        $this->setRawCalendarContextData(Context::EventDragAndDrop, $data);

        // The lock flag is now trusted (part of the verified signature), so a client cannot strip a
        // per-event lock to move an event the developer pinned in place.
        if ((bool) $this->getRawCalendarContextData('event.extendedProps.dragLocked')) {
            return false;
        }

        return $this->onEventDrop($this->getCalendarContextInfo(), $this->getEventRecord());
    }
}
