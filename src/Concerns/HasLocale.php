<?php

namespace Guava\Calendar\Concerns;

trait HasLocale
{
    /**
     * Defines the locale parameter for the native JavaScript Intl.DateTimeFormat object that EventCalendar uses to format date and time strings in options such as dayHeaderFormat, eventTimeFormat, etc.
     */
    protected ?string $locale = null;

    /**
     * Defines the locale parameter for the native JavaScript Intl.DateTimeFormat object that EventCalendar uses to format date and time strings in options such as dayHeaderFormat, eventTimeFormat, etc.
     */
    public function getLocale(): string
    {
        return $this->locale ?? $this->getDefaultLocale();
    }

    private function getDefaultLocale(): string
    {
        return str(app()->getLocale())
            ->before('_')
            ->toString()
        ;
    }
}
