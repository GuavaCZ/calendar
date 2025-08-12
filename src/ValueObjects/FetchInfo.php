<?php

namespace Guava\Calendar\ValueObjects;

use Carbon\Carbon;

class FetchInfo
{
    protected Carbon $start;

    protected Carbon $end;

    protected array $original = [];

    public function __construct(array $data)
    {
        $this->original = $data;

        $this->start = Carbon::make($data['start']);
        $this->end = Carbon::make($data['end']);
    }
}
