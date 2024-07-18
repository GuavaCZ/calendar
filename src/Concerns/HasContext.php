<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Enums\Context;

trait HasContext
{
    protected ?Context $context = null;

    public function context(Context $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ?Context
    {
        return $this->evaluate($this->context);
    }
}
