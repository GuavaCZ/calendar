<?php

namespace Guava\Calendar\Concerns;

trait HasLocale
{
    protected ?string $locale = null;

    public function locale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->evaluate($this->locale) ?? $this->getDefaultLocale();
    }

    private function getDefaultLocale(): string
    {
        return str(app()->getLocale())
            ->before('_')
            ->toString()
        ;
    }
}
