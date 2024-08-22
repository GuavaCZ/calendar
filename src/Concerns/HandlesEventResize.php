<?php

namespace Guava\Calendar\Concerns;

trait HandlesEventResize
{
    protected bool $eventResizeEnabled = false;

    public function onEventResize(array $info = []): bool
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

    public function eventResizeEnabled(bool $enabled = true): static
    {
        $this->eventResizeEnabled = $enabled;

        return $this;
    }

    public function isEventResizeEnabled(): bool
    {
        return $this->eventResizeEnabled;
    }
}
