<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\ValueObjects\EventClickInfo;

trait HandlesEventClick
{
    protected bool $eventClickEnabled = false;

    protected ?string $defaultEventClickAction = 'view';

    /**
     * @throws \Exception
     */
    protected function onEventClick(EventClickInfo $info, ?string $action = null): void
    {
        // No action to trigger
        if (! $action) {
            return;
        }

        $this->mountCalendarAction($action, $info);
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
    public function onEventClickJs(array $info = [], ?string $action = null): void
    {
        // Check if event click is enabled
        if (! $this->isEventClickEnabled()) {
            return;
        }

        $action ??= $this->getMountedActionContextData('event.extendedProps.action');
        $action ??= $this->getDefaultEventClickAction();

        $this->setMountedActionContextData($info);
        $this->resolveEventRecord();

        $this->onEventClick(
            new EventClickInfo(
                $info,
                $this->getEventRecord(),
                $this->shouldUseFilamentTimezone()
            ),
            $action
        );
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
