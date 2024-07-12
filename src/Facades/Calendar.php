<?php

namespace Guava\Calendar\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Guava\Calendar\Calendar
 */
class Calendar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Guava\Calendar\Calendar::class;
    }
}
