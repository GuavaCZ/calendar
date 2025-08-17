<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Illuminate\Database\Eloquent\Model;

trait HandlesEventClick
{
    protected bool $eventClickEnabled = false;

    protected ?string $defaultEventClickAction = 'view';

    /**
     * @throws \Exception
     */
    protected function onEventClick(EventClickInfo $info, Model $event, ?string $action = null): void
    {
        // No action to trigger
        if (! $action) {
            return;
        }

        $this->mountAction($action);
    }

    public function isEventClickEnabled(): bool
    {
        return $this->eventClickEnabled;
    }

    public function getDefaultEventClickAction(): ?string
    {
        return $this->evaluate($this->defaultEventClickAction);
    }

    /**
     * @internal Do not override, internal purpose only. Use `onEventClick` instead
     */
    public function onEventClickJs(array $data = [], ?string $action = null): void
    {
        // Check if event click is enabled
        if (! $this->isEventClickEnabled()) {
            return;
        }

        $this->setRawCalendarContextData(Context::EventClick, $data);

        $action ??= $this->getRawCalendarContextData('event.extendedProps.action');
        $action ??= $this->getDefaultEventClickAction();

        // TODO: Similar to how Schemas work, allow users to define a method for each Event Model Type
        // TODO: using attributes. such as #[CalendarEventClick(Sprint::class)] above a method
        // TODO: such as onSprintEventClick would be only called for Sprint.
        $this->onEventClick($this->getCalendarContextInfo(), $this->getEventRecord(), $action);
    }

    // TODO: Might be worth looking into to automatically choose between view /edit action based on the permissions
    //
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
