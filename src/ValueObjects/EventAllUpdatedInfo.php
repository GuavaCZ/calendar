<?php

namespace Guava\Calendar\ValueObjects;

readonly class EventAllUpdatedInfo
{
    public CalendarView $view;

    protected array $originalData;

    public function __construct(array $data, bool $useFilamentTimezone)
    {
        $this->originalData = $data;

        $this->view = new CalendarView(
            data_get($data, 'view'),
            data_get($data, 'tzOffset'),
            $useFilamentTimezone
        );
    }
}
