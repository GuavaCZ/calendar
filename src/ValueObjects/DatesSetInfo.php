<?php

namespace Guava\Calendar\ValueObjects;

use Carbon\CarbonImmutable;

use function Guava\Calendar\utc_to_user_local_time;

readonly class DatesSetInfo
{
    public CarbonImmutable $start;

    public CarbonImmutable $end;

    public CalendarView $view;

    protected array $originalData;

    public function __construct(array $data, bool $useFilamentTimezone)
    {
        $this->originalData = $data;

        $this->start = utc_to_user_local_time(
            data_get($data, 'start'),
            data_get($data, 'tzOffset'),
            $useFilamentTimezone
        );

        $this->end = utc_to_user_local_time(
            data_get($data, 'end'),
            data_get($data, 'tzOffset'),
            $useFilamentTimezone
        );

        $this->view = new CalendarView(
            data_get($data, 'view'),
            data_get($data, 'tzOffset'),
            $useFilamentTimezone
        );
    }
}
