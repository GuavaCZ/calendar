<?php

namespace Guava\Calendar;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Filament\Support\Facades\FilamentTimezone;

if (! function_exists('Guava\Calendar\calendar_event_signature')) {
    /**
     * Sign the identifying triple of a model-backed event (model class, key and action) so the
     * server can detect tampering when the browser sends it back. The values round-trip through
     * the client in [extendedProps] and would otherwise let a crafted request resolve arbitrary
     * records or trigger arbitrary actions. Truncated to 128 bits, which is ample: forging a
     * 128-bit HMAC is infeasible, and it keeps the per-event payload small.
     */
    function calendar_event_signature(string $model, int | string $key, string $action = ''): string
    {
        $payload = implode('|', [$model, $key, $action]);

        return substr(hash_hmac('sha256', $payload, (string) config('app.key')), 0, 32);
    }
}

if (! function_exists('Guava\Calendar\to_carbon')) {
    /**
     * Normalize any accepted date input into a mutable Carbon instance while preserving its
     * timezone. A CarbonImmutable (or any non-Carbon DateTimeInterface) must not be passed
     * through a `string | Carbon` union directly: in non-strict mode PHP would coerce it to a
     * timezone-less string and silently drop the offset. An existing Carbon is returned as-is
     * (identity preserved) rather than cloned.
     */
    function to_carbon(string | CarbonInterface $date): Carbon
    {
        return match (true) {
            $date instanceof Carbon => $date,
            $date instanceof CarbonInterface => Carbon::instance($date),
            default => Carbon::parse($date),
        };
    }
}

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
            $date = CarbonImmutable::parse($date);
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
            $date = CarbonImmutable::parse($date);
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
    function utc_to_user_local_time(CarbonImmutable | string $date, int $timezoneOffset, bool $useFilamentTimezone = false, ?string $timezone = null): CarbonImmutable
    {
        if (is_string($date)) {
            $date = CarbonImmutable::parse($date);
        }

        // This converts the UTC time to the user's local time, by offsetting the timezone
        // This will result in a time in the user's local browser time
        $date = $date->utcOffset($timezoneOffset);

        // This will shift the timezone to the timezone set by filament (or the explicit
        // per-event timezone, when provided), essentially treating the user's local browser
        // timezone as that one. This basically overrides the timezone and "adds support" for
        // setting the timezone despite EventCalendar not supporting timezones. It must be the
        // inverse of the timezone used when serializing the event in toCalendarObject().
        if ($useFilamentTimezone) {
            $date = $date->shiftTimezone($timezone ?? FilamentTimezone::get());
        }

        return $date;
    }
}
