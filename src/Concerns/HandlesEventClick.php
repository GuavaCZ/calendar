<?php

namespace Guava\Calendar\Concerns;

trait HandlesEventClick
{
    protected ?string $defaultEventClickAction = 'view';

    public function defaultEventClickAction(string $action): static
    {
        $this->defaultEventClickAction = $action;

        return $this;
    }

    public function getDefaultEventClickAction(): ?string
    {
        return $this->evaluate($this->defaultEventClickAction);
    }

    public function onEventClick($info): void
    {
        $model = data_get($info, 'event.extendedProps.model');
        $key = data_get($info, 'event.extendedProps.key');

        if ($model && $key) {
            $record = $this->resolveRecord(
                data_get($info, 'event.extendedProps.model'),
                data_get($info, 'event.extendedProps.key'),
            );

            if ($action = data_get($info, 'event.extendedProps.action', $this->getDefaultEventClickAction())) {
                $this->authorize(match ($action) {
                    'edit' => 'update',
                    default => $action,
                }, [$record]);

                $this->mountAction($action, [
                    'event' => data_get($info, 'event', []),
                ]);
            }
        }
    }
}
