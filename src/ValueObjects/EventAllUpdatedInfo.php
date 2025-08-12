<?php

namespace Guava\Calendar\ValueObjects;

use Carbon\CarbonImmutable;

use function Guava\Calendar\utc_to_user_local_time;

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
