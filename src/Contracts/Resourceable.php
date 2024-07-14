<?php

namespace Guava\Calendar\Contracts;

use Guava\Calendar\ValueObjects\Resource;

interface Resourceable
{
    public function toResource(): array | Resource;
}
