<?php

namespace Guava\Calendar\ValueObjects;

use Carbon\CarbonImmutable;

use function Guava\Calendar\browser_date_to_app_date;

readonly class FetchInfo
{
    public CarbonImmutable $start;

    public CarbonImmutable $end;

    protected array $originalData;

    public function __construct(array $data)
    {
        $this->originalData = $data;

        $this->start = browser_date_to_app_date(CarbonImmutable::make($data['startStr']));
        $this->end = browser_date_to_app_date(CarbonImmutable::make($data['endStr']));
    }
}
