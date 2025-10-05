<?php

namespace Guava\Calendar\Enums;

enum Context: string
{
    case DateClick = 'dateClick';
    case DateSelect = 'dateSelect';
    case EventClick = 'eventClick';
    case NoEventsClick = 'noEventsClick';
    case EventResize = 'eventResize';
    case EventDragAndDrop = 'eventDragAndDrop';

    public function interactsWithRecord(): bool
    {
        return match ($this) {
            self::EventClick, self::EventResize, self::EventDragAndDrop => true,
            default => false
        };
    }
}
