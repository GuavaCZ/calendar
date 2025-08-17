<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\ValueObjects\DatesSetInfo;

trait HandlesDatesSet
{
    /**
     * Sets whether the Dates Set callback should be enabled or not.
     *
     * To enable the Dates Set callback, set this to true and override `onDatesSet` to implement your logic.
     */
    protected bool $datesSetEnabled = false;

    /**
     * Implement your dates set callback logic here.
     *
     * This method will only be fired when `$datesSetEnabled` is set to true.
     *
     * When enabled, this will be called when the date range of the calendar was originally set or changed by clicking the previous/next buttons, changing the view, manipulating the current date via the API, etc.
     *
     * @param  DatesSetInfo  $info  contains information about the current calendar view
     */
    protected function onDatesSet(DatesSetInfo $info): void {}

    /**
     * Sets whether the Dates Set callback be enabled or not.
     *
     * To enable the Dates Set callback, return true and override `onDatesSet` to implement your logic.
     */
    public function isDatesSetEnabled(): bool
    {
        return $this->datesSetEnabled;
    }

    /**
     * @internal Do not override, internal purpose only. Use `onDatesSet` instead
     */
    public function onDatesSetJs(array $data): void
    {
        // Check if dates set is enabled
        if (! $this->isDatesSetEnabled()) {
            return;
        }

        $this->onDatesSet(new DatesSetInfo($data, $this->shouldUseFilamentTimezone()));
    }
}
