<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Contracts\HasCalendar;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\DateSelectInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\NoEventsClickInfo;

trait CalendarAction
{
    protected function resolveDefaultClosureDependencyForEvaluationByType(string $parameterType): array
    {
        /** @var InteractsWithCalendar $livewire */
        $livewire = $this->getLivewire();

        // Action is used outside the calendar
        if (! ($livewire instanceof HasCalendar)) {
            return parent::resolveDefaultClosureDependencyForEvaluationByType($parameterType);
        }

        return match ($parameterType) {
            DateClickInfo::class, DateSelectInfo::class, EventClickInfo::class, NoEventsClickInfo::class, ContextualInfo::class => [
                $livewire->getCalendarContextInfo(),
            ],
            default => parent::resolveDefaultClosureDependencyForEvaluationByType($parameterType),
        };
    }
}
