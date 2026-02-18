<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Contracts\HasCalendar;
use Guava\Calendar\Enums\Context;
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

        $expectedContext = match ($parameterType) {
            DateClickInfo::class => Context::DateClick,
            DateSelectInfo::class => Context::DateSelect,
            EventClickInfo::class => Context::EventClick,
            NoEventsClickInfo::class => Context::NoEventsClick,
            ContextualInfo::class => null,
            default => false,
        };

        if ($expectedContext !== false) {
            $contextInfo = $livewire->getCalendarContextInfo();
            return ($expectedContext === null || $contextInfo->getContext() === $expectedContext)
                ? [$contextInfo]
                : [null];
        }

        return parent::resolveDefaultClosureDependencyForEvaluationByType($parameterType);
    }
}
