<?php

namespace Guava\Calendar\Concerns;

use Exception;

use function Filament\get_authorization_response;

trait HandlesEventClick
{
    protected bool $eventClickEnabled = false;

    protected ?string $defaultEventClickAction = 'view';

    /**
     * @throws \Exception
     */
    public function onEventClick(array $info = [], ?string $action = null): void
    {
        // Check if event click is enabled
        if (! $this->isEventClickEnabled()) {
            return;
        }

        $model = data_get($info, 'event.extendedProps.model');
        $key = data_get($info, 'event.extendedProps.key');

        // Cannot resolve event record
        if (! $model || ! $key) {
            throw new Exception('Event click requires a [model] and [key] set in the [extendedProps] of the event to work.');
        }

        $this->resolveEventRecord($model, $key);

        $action ??= data_get($info, 'event.extendedProps.action') ?? $this->getDefaultEventClickAction();

        // No action to trigger
        if (! $action) {
            return;
        }

        $response = $this->getAuthorizationResponse($action, $this->getEventRecord());

        // Action is not allowed
        if (! $response->allowed()) {
            $this->sendUnauthorizedNotification($response);

            return;
        }

        $this->mountAction($action, [
            'event' => data_get($info, 'event', []),
        ]);
    }

    public function isEventClickEnabled(): bool
    {
        return $this->eventClickEnabled;
    }

    public function getDefaultEventClickAction(): ?string
    {
        return $this->evaluate($this->defaultEventClickAction);
    }

    //    protected function resolveDefaultEventClickAction() {
    //        foreach (['view', 'edit'] as $action) {
    //            $action = $this->getAction($action);
    //
    //            if (! $action) {
    //                continue;
    //            }
    //
    //            $action = clone $action;
    //
    //            $action->record($record);
    //            $action->getGroup()?->record($record);
    //
    //            if ($action->isHidden()) {
    //                continue;
    //            }
    //
    //            $url = $action->getUrl();
    //
    //            if (! $url) {
    //                continue;
    //            }
    //
    //            return $url;
    //        }
    //
    //    }
}
