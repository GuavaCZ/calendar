<?php

namespace Guava\Calendar\Concerns;

trait HandlesDateSelect
{
    protected bool $dateSelectEnabled = false;

    public function onDateSelect(array $info = []): void {}

    public function dateSelectEnabled(bool $enabled = true): static
    {
        $this->dateSelectEnabled = $enabled;

        return $this;
    }

    public function isDateSelectEnabled(): bool
    {
        return $this->dateSelectEnabled;
    }
}
