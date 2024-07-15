<?php

namespace Guava\Calendar\Concerns;

use Closure;

trait HasOptions
{
    protected array | Closure $options = [];

    public function options(array | Closure $options): static
    {
        $this->options = $options;
    }

    public function getOptions(): array
    {
        return $this->evaluate($this->options);
    }
}
