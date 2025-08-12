<?php

namespace Guava\Calendar\Concerns;

use Carbon\WeekDay;

trait HasFirstDay
{
    /**
     * The day that each week begins at, where Sunday is 0, Monday is 1, etc. Saturday is 6.
     */
    protected WeekDay $firstDay = WeekDay::Monday;

    /**
     * The day that each week begins at, where Sunday is 0, Monday is 1, etc. Saturday is 6.
     */
    public function getFirstDay(): WeekDay
    {
        return $this->firstDay;
    }
}
