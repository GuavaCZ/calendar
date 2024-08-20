<?php

namespace Guava\Calendar\Concerns;

trait HandlesEventDragAndDrop
{
    protected bool $eventDragEnabled = false;

    public function onEventDrop(array $info = []): bool
    {
        $model = data_get($info, 'event.extendedProps.model');
        $key = data_get($info, 'event.extendedProps.key');

        if ($model && $key) {
            $this->resolveEventRecord(
                data_get($info, 'event.extendedProps.model'),
                data_get($info, 'event.extendedProps.key'),
            );
        }

        return true;
    }

    public function eventDragEnabled(bool $enabled = true): static
    {
        $this->eventDragEnabled = $enabled;

        return $this;
    }

    public function isEventDragEnabled(): bool
    {
        return $this->eventDragEnabled;
    }
}
