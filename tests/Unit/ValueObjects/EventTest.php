<?php

use Carbon\Carbon;
use Guava\Calendar\ValueObjects\CalendarEvent;

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

it('should return props to array', function () {
    $start = Carbon::now();
    $end = Carbon::now()->addHour();
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

    $array = $this->event->toArray();

    expect($array)->toMatchArray([
        'title' => $title,
        'start' => $start,
        'end' => $end,
        'backgroundColor' => $backgroundColor,
        'textColor' => $textColor,
        'styles' => 'color: red; font-size: 12px; border-color: red;',
        'classNames' => 'class-1 class-2',
        'resourceIds' => $resourceIds,
        'extendedProps' => $extendedProps,
    ]);
});
