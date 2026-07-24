<?php

use Carbon\Carbon;
use Filament\Support\Facades\FilamentTimezone;
use Guava\Calendar\ValueObjects\CalendarEvent;

/**
 * Serialize an event to the JS boundary and back, mirroring a drag/drop round-trip, and return
 * the reconstructed event. The browser offset is assumed to match the timezone used for display
 * (as it does in the useFilamentTimezone design), so a correct implementation must recover the
 * exact same instant.
 */
function roundTripEvent(CalendarEvent $event, int $offset, bool $useFilamentTimezone): CalendarEvent
{
    $data = $event->toCalendarObject($offset, $useFilamentTimezone);

    return CalendarEvent::make()->fromCalendarObject($data, $offset, $useFilamentTimezone);
}

it('round-trips an event through the raw offset path', function () {
    $start = Carbon::parse('2026-07-01 10:00:00', 'UTC');

    $event = CalendarEvent::make()
        ->title('x')
        ->displayAuto()
        ->start($start)
        ->end($start->copy()->addHour())
    ;

    $result = roundTripEvent($event, 120, false);

    expect($result->getStart()->utc()->toIso8601String())->toBe($start->utc()->toIso8601String())
        ->and($result->getEnd()->utc()->toIso8601String())->toBe($start->copy()->addHour()->utc()->toIso8601String())
    ;
});

it('round-trips an event through the filament timezone path', function () {
    FilamentTimezone::set('Europe/Prague'); // UTC+2 on this summer date

    $start = Carbon::parse('2026-07-01 10:00:00', 'UTC');

    $event = CalendarEvent::make()
        ->title('x')
        ->displayAuto()
        ->start($start)
        ->end($start->copy()->addHour())
    ;

    // Browser offset matches the Filament timezone offset, per the useFilamentTimezone design.
    $result = roundTripEvent($event, 120, true);

    expect($result->getStart()->utc()->toIso8601String())->toBe($start->utc()->toIso8601String());
});

it('round-trips an event carrying an explicit per-event timezone', function () {
    FilamentTimezone::set('Europe/Prague'); // deliberately different from the event timezone

    $start = Carbon::parse('2026-07-01 12:00:00', 'UTC');

    $event = CalendarEvent::make()
        ->title('x')
        ->displayAuto()
        ->timezone('America/New_York') // UTC-4 on this summer date
        ->start($start)
        ->end($start->copy()->addHour())
    ;

    $data = $event->toCalendarObject(-240, true);

    // The per-event timezone must be serialized across the boundary so the inbound path can invert it.
    expect($data['extendedProps']['timezone'])->toBe('America/New_York');

    $result = CalendarEvent::make()->fromCalendarObject($data, -240, true);

    // Without carrying the timezone, the inbound path would shift by Europe/Prague and land on the
    // wrong instant (06:00 UTC instead of 12:00 UTC).
    expect($result->getStart()->utc()->toIso8601String())->toBe($start->utc()->toIso8601String())
        ->and($result->getTimezone())->toBe('America/New_York');
});
