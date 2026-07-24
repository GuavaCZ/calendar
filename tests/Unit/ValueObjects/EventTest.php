<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Support\HtmlString;

beforeEach(function () {
    $this->event = CalendarEvent::make();
});

it('should set start and end', function () {
    $start = Carbon::now();
    $end = Carbon::now()->addHour();

    $this->event->start($start);
    $this->event->end($end);

    expect($this->event->getStart())->toBe($start);
    expect($this->event->getEnd())->toBe($end);
});

it('should set all day', function () {
    $this->event->allDay(true);

    expect($this->event->getAllDay())->toBeTrue();
});

it('should set the title', function () {
    $title = 'Test Event';
    $this->event->title($title);

    expect($this->event->getTitle())->toBe($title);
});

it('should set the html title', function () {
    $title = new HtmlString('<strong>Test Event</strong>');
    $this->event->title($title);

    expect($this->event->getTitle())->toBe($title);
});

it('should set the background color', function () {
    $color = '#ff0000';
    $this->event->backgroundColor($color);

    expect($this->event->getBackgroundColor())->toBe($color);
});

it('should set the text color', function () {
    $color = '#00ff00';
    $this->event->textColor($color);

    expect($this->event->getTextColor())->toBe($color);
});

it('should set the styles', function () {
    $styles = [
        'color: red',
        'display: flex; flex-direction: row;',
        'font-size' => '12px',
        'opacity' => 1,
        'background-color: blue' => false,
        'border-color: red' => true,
    ];
    $this->event->styles($styles);

    expect($this->event->getStyles())->toBe('color: red; display: flex; flex-direction: row; font-size: 12px; opacity: 1; border-color: red;');
});

it('should set some classes', function () {
    $classes = ['class-1', 'class-2' => true, 'class3' => false];
    $this->event->classNames($classes);

    expect($this->event->getClassNames())->toBe('class-1 class-2');
});

it('should set the display', function () {
    $display = 'block';
    $this->event->display($display);

    expect($this->event->getDisplay())->toBe($display);
});

it('should set the editable features', function () {
    $this->event->editable(true);
    expect($this->event->getEditable())->toBeTrue();

    $this->event->startEditable(true);
    expect($this->event->getStartEditable())->toBeTrue();

    $this->event->durationEditable(true);
    expect($this->event->getDurationEditable())->toBeTrue();
});

it('only serializes restricting editable flags so the global flag stays authoritative', function () {
    $base = fn () => CalendarEvent::make()->title('x')->start(Carbon::now())->end(Carbon::now()->addHour());

    // A per-event "true" is dropped — it must not enable an interaction the global flag disabled.
    expect($base()->startEditable(true)->durationEditable(true)->editable(true)->toCalendarObject(0, false))
        ->not->toHaveKey('startEditable')
        ->not->toHaveKey('durationEditable')
    ;

    // A per-event "false" (a lock) is forwarded so EventCalendar disables that specific event.
    expect($base()->startEditable(false)->toCalendarObject(0, false))
        ->toMatchArray(['startEditable' => false])
        ->not->toHaveKey('durationEditable')
    ;

    // editable(false) locks both axes.
    expect($base()->editable(false)->toCalendarObject(0, false))
        ->toMatchArray(['startEditable' => false, 'durationEditable' => false])
    ;
});

it('should set resource ids', function () {
    $resourceIds = [1, 2, 3];
    $this->event->resourceIds($resourceIds);

    expect($this->event->getResourceIds())->toBe($resourceIds);
});

it('should set some extended props', function () {
    $props = ['key' => 'value', 'another_key' => 'another_value'];
    $this->event->extendedProps($props);

    expect($this->event->getExtendedProps())->toBe($props);
});

it('should serialize to a calendar object', function () {
    $start = Carbon::parse('2026-07-01 10:00:00', 'UTC');
    $end = Carbon::parse('2026-07-01 11:00:00', 'UTC');
    $title = 'Test Event';
    $backgroundColor = '#ff0000';
    $textColor = '#00ff00';
    $styles = ['color' => 'red', 'font-size' => '12px', 'background-color' => false, 'border-color: red' => true];
    $classNames = ['class-1', 'class-2' => true, 'class-3' => false];
    $resourceIds = [1, 2, 3];
    $extendedProps = ['key' => 'value', 'another_key' => 'another_value'];

    $this->event->start($start)->end($end)->title($title)->backgroundColor($backgroundColor)
        ->textColor($textColor)->styles($styles)->classNames($classNames)->resourceIds($resourceIds)->extendedProps($extendedProps)
    ;

    $array = $this->event->toCalendarObject(0, false);

    expect($array)->toMatchArray([
        'title' => $title,
        'start' => $start->copy()->utcOffset(0)->toIso8601String(),
        'end' => $end->copy()->utcOffset(0)->toIso8601String(),
        'backgroundColor' => $backgroundColor,
        'textColor' => $textColor,
        'styles' => 'color: red; font-size: 12px; border-color: red;',
        'classNames' => 'class-1 class-2',
        'resourceIds' => $resourceIds,
        'extendedProps' => $extendedProps,
    ]);
});

it('does not mutate the stored start/end when serializing', function () {
    $start = Carbon::parse('2026-07-01 10:00:00', 'UTC');
    $end = Carbon::parse('2026-07-01 11:00:00', 'UTC');

    $this->event->title('x')->start($start)->end($end);
    $this->event->toCalendarObject(120, false);

    expect($this->event->getStart()->toIso8601String())->toBe($start->toIso8601String())
        ->and($this->event->getEnd()->toIso8601String())->toBe($end->toIso8601String())
    ;
});

it('preserves the timezone of an immutable date passed to start/end', function () {
    // A CarbonImmutable must not be coerced to a timezone-less string by the string|Carbon union.
    $start = CarbonImmutable::parse('2026-07-01 08:00:00', 'America/New_York');

    $this->event->start($start)->end($start->addHour());

    expect($this->event->getStart()->toIso8601String())->toBe('2026-07-01T08:00:00-04:00')
        ->and($this->event->getStart())->toBeInstanceOf(Carbon::class)
    ;
});

it('should return title with string', function () {
    $title = 'Test Event';

    $this->event->title($title)->start(Carbon::now())->end(Carbon::now()->addHour());

    expect($this->event->toCalendarObject(0, false))
        ->toMatchArray([
            'title' => 'Test Event',
        ])
    ;
});

it('should return html props with htmlable', function () {
    $title = new HtmlString('<strong>Test Event</strong>');

    $this->event->title($title)->start(Carbon::now())->end(Carbon::now()->addHour());

    expect($this->event->toCalendarObject(0, false))
        ->toMatchArray([
            'title' => [
                'html' => '<strong>Test Event</strong>',
            ],
        ])
    ;
});
