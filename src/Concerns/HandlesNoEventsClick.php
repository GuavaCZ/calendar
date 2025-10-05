<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\NoEventsClickInfo;

trait HandlesNoEventsClick
{
    protected bool $noEventsClickEnabled = false;

    protected function onNoEventsClick(NoEventsClickInfo $info): void {}

    public function isNoEventsClickEnabled(): bool
    {
        return $this->noEventsClickEnabled;
    }

    /**
     * @internal Do not override, internal purpose only. Use `onDateClick` instead
     */
    public function onNoEventsClickJs(array $data): void
    {
        // Check if no events click is enabled
        if (! $this->isNoEventsClickEnabled()) {
            return;
        }

        $this->setRawCalendarContextData(Context::NoEventsClick, $data);

        $this->onNoEventsClick($this->getCalendarContextInfo());
    }
}
