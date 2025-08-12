<?php

namespace Guava\Calendar\Concerns;

trait CanUseFilamentTimezone
{
    protected bool $useFilamentTimezone = false;

    public function shouldUseFilamentTimezone(): bool
    {
        return $this->useFilamentTimezone;
    }
}
