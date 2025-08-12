<?php

namespace Guava\Calendar\ValueObjects;

use Illuminate\Database\Eloquent\Model;

readonly class EventResizeInfo
{
    public Model $record;

    public CalendarEvent $event;

    public CalendarEvent $oldEvent;

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

        $this->oldEvent = CalendarEvent::make($record)
            ->fromCalendarObject(
                data_get($data, 'oldEvent'),
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
}
