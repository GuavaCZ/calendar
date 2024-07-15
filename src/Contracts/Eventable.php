<?php

namespace Guava\Calendar\Contracts;

use Guava\Calendar\ValueObjects\Event;

interface Eventable
{
    public function toEvent(): array | Event;
}
