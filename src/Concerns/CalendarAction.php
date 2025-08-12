<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\DateSelectInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\NoEventsClickInfo;

trait CalendarAction
{
    protected ?Closure $mountInCalendarUsing = null;

    /**
     * Use to access the contextual data of the calendar when mounting the action.
     *
     * Use this instead of `mountUsing()` when working in the context of the calendar.
     *
     * Based on the context, you are able to access the following properties as arguments of the callback:
     *  - DateClickInfo
     *  - DateSelectInfo
     *  - EventClickInfo
     *  - NoEventClickInfo
     *  - Context - the current context of the action
     *
     * @return $this
     */
    public function mountInCalendarUsing(Closure $callback): static
    {
        $this->mountInCalendarUsing = $callback;

        return $this
            ->mountUsing(function (Schema $schema, $parameters, array $arguments) {
                $context = Context::tryFrom(data_get($arguments, 'context'));
                $data = data_get($arguments, 'data') ?? [];
                $useFilamentTimezone = data_get($arguments, 'useFilamentTimezone') ?? false;

                $schema->fill();

                $this->callMountInCalendarUsing($schema, $context, $data, $useFilamentTimezone);
            })
        ;
    }

    /**
     * @internal Do not override, internal purpose only. See `mountInCalendarUsing()`
     */
    public function callMountInCalendarUsing(Schema $schema, ?Context $context, array $data, bool $useFilamentTimezone): void
    {
        $dateClickInfo = $context === Context::DateClick ? new DateClickInfo($data, $useFilamentTimezone) : null;
        $dateSelectInfo = $context === Context::DateSelect ? new DateSelectInfo($data, $useFilamentTimezone) : null;
        $eventClickInfo = $context === Context::EventClick ? new EventClickInfo($data, $this->getLivewire()->getEventRecord(), $this->getLivewire()->shouldUseFilamentTimezone()) : null;
        $noEventsClickInfo = $context === Context::NoEventsClick ? new NoEventsClickInfo($data, $useFilamentTimezone) : null;

        $this->evaluate($this->mountInCalendarUsing, [
            'context' => $context,
            'dateClickInfo' => $dateClickInfo,
            'dateSelectInfo' => $dateSelectInfo,
            'eventClickInfo' => $eventClickInfo,
            'noEventsClickInfo' => $noEventsClickInfo,
            'form' => $schema,
            'schema' => $schema,
        ], [
            DateClickInfo::class => $dateClickInfo,
            DateSelectInfo::class => $dateSelectInfo,
            EventClickInfo::class => $eventClickInfo,
            NoEventsClickInfo::class => $noEventsClickInfo,
            ContextualInfo::class => match ($context) {
                Context::DateClick => $dateClickInfo,
                Context::DateSelect => $dateSelectInfo,
                Context::EventClick => $eventClickInfo,
                Context::NoEventsClick => $noEventsClickInfo,
            },
            Form::class => $schema,
            Schema::class => $schema,
        ]);
    }
}
