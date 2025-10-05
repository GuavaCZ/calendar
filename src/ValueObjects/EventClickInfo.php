<?php

namespace Guava\Calendar\ValueObjects;

use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Enums\Context;
use Illuminate\Database\Eloquent\Model;

readonly class EventClickInfo implements ContextualInfo
{
    public Model $record;

    public CalendarEvent $event;

    public CalendarView $view;

    protected array $originalData;

    public function __construct(array $data, Model $record, bool $useFilamentTimezone)
    {
        $this->originalData = $data;

        $this->record = $record;

        $this->event = CalendarEvent::make($record)
            ->fromCalendarObject(
                data_get($data, 'event'),
                data_get($data, 'tzOffset'),
                $useFilamentTimezone
            )
        ;

        $this->view = new CalendarView(
            data_get($data, 'view'),
            data_get($data, 'tzOffset'),
            $useFilamentTimezone
        );
    }

    public function getContext(): Context
    {
        return Context::EventClick;
    }
}
