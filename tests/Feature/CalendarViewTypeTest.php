<?php

use Guava\Calendar\Enums\CalendarViewType;

/**
 * An unknown view makes CalendarView::$type null, which is non-nullable, so the calendar crashes.
 * Skipped unless node_modules is installed, so a PHP-only checkout still runs the suite.
 */
function eventCalendarBundle(): string
{
    return dirname(__DIR__, 2).'/node_modules/@event-calendar/core/dist/index.js';
}

/**
 * Collects the keys of every `assign(options.views, {...})` block the plugins declare, so a view
 * named unlike the current ones is still picked up.
 */
function libraryCalendarViews(): array
{
    $source = file_get_contents(eventCalendarBundle());
    $views = [];
    $offset = 0;

    while (($match = strpos($source, 'assign(options.views,', $offset)) !== false) {
        $start = strpos($source, '{', $match);
        $depth = 0;

        for ($end = $start; $end < strlen($source); $end++) {
            match ($source[$end]) {
                '{' => $depth++,
                '}' => $depth--,
                default => null,
            };

            if ($depth === 0) {
                break;
            }
        }

        $body = substr($source, $start + 1, $end - $start - 1);
        $offset = $end;

        preg_match_all('/([A-Za-z_]\w*)\s*:/', $body, $keys, PREG_OFFSET_CAPTURE);

        foreach ($keys[1] as [$name, $position]) {
            $preceding = substr($body, 0, $position);

            // Only keys at the top level of the block are views; the rest belong to a view's options.
            if (substr_count($preceding, '{') === substr_count($preceding, '}')) {
                $views[] = $name;
            }
        }
    }

    return collect($views)->unique()->sort()->values()->all();
}

it('matches the views the calendar library registers', function () {
    $enum = collect(CalendarViewType::cases())->pluck('value')->sort()->values()->all();

    expect($enum)->toBe(libraryCalendarViews());
})->skip(
    fn () => ! file_exists(eventCalendarBundle()),
    'Run npm install to check the enum against @event-calendar/core.',
);
