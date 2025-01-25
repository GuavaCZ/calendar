<?php

namespace Guava\Calendar\Concerns;

use Illuminate\Auth\Access\AuthorizationException;

trait HandlesEventClick
{
    protected bool $eventClickEnabled = false;

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

    public function onEventClick(array $info = [], ?string $action = null): void
    {
        try {
            $model = data_get($info, 'event.extendedProps.model');
            $key = data_get($info, 'event.extendedProps.key');

            if ($model && $key) {
                $this->resolveEventRecord($model, $key);

                $action ??= data_get($info, 'event.extendedProps.action', $this->getDefaultEventClickAction());

                if ($action) {
                    $this->authorize(match ($action) {
                        'edit' => 'update',
                        default => $action,
                    }, [$this->eventRecord]);

                    $this->mountAction($action, [
                        'event' => data_get($info, 'event', []),
                    ]);
                }
            }
        } catch (AuthorizationException $e) {
            return;
        }
    }

    public function eventClickEnabled(bool $enabled = true): static
    {
        $this->eventClickEnabled = $enabled;

        return $this;
    }

    public function isEventClickEnabled(): bool
    {
        return $this->eventClickEnabled;
    }
}
