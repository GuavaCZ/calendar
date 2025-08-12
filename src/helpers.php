<?php

namespace Guava\Calendar;

use Carbon\CarbonImmutable;
use Filament\Support\Facades\FilamentTimezone;

if (! function_exists('Guava\Calendar\browser_date_to_user_date')) {
    /**
     * The underlying EventCalendar does not support timezones and thus all times in the calendar
     * are provided in either UTC or their local browser locale.
     *
     * To work out of the box in any filament installation and support filament v4 timezone settings,
     * this method converts the users local browser date into the filament timezone and then converts
     * it into the apps timezone.
     */
    function browser_date_to_user_date(CarbonImmutable | string $date): CarbonImmutable
    {
        if (is_string($date)) {
            $date = CarbonImmutable::make($date);
        }

        return $date->shiftTimezone(FilamentTimezone::get());
    }
}

if (! function_exists('Guava\Calendar\browser_date_to_app_date')) {
    /**
     * The underlying EventCalendar does not support timezones and thus all times in the calendar
     * are provided in either UTC or their local browser locale.
     *
     * To work out of the box in any filament installation and support filament v4 timezone settings,
     * this method converts the users local browser date into the filament timezone and then converts
     * it into the apps timezone.
     */
    function browser_date_to_app_date(CarbonImmutable | string $date): CarbonImmutable
    {
        if (is_string($date)) {
            $date = CarbonImmutable::make($date);
        }

        return browser_date_to_user_date($date)->setTimezone(config('app.timezone'));
    }
}

if (! function_exists('Guava\Calendar\utc_to_user_local_time')) {
    /**
     * The underlying EventCalendar does not support timezones and thus all times in the calendar
     * are provided in either UTC or their local browser locale.
     *
     * To work out of the box in any filament installation and support filament v4 timezone settings,
     * this method converts the users local browser date into the filament timezone and then converts
     * it into the apps timezone.
     */
    function utc_to_user_local_time(CarbonImmutable | string $date, int $timezoneOffset, bool $useFilamentTimezone = false): CarbonImmutable
    {
        if (is_string($date)) {
            $date = CarbonImmutable::make($date);
        }

        // This converts the UTC time to the user's local time, by offsetting the timezone
        // This will result in a time in the user's local browser time
        $date = $date->utcOffset($timezoneOffset);

        // This will shift the timezone to the timezone set by filament,
        // essentially treating the user's local browser timezone as the one set by filament
        // This basically overrides the timezone and "adds support" for setting the timezone
        // despite EventCalendar not supporting timezones.
        if ($useFilamentTimezone) {
            $date = $date->shiftTimezone(FilamentTimezone::get());
        }

        return $date;
    }
}
