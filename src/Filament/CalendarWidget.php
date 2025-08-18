<?php

namespace Guava\Calendar\Filament;

use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Widgets\Widget;
use Guava\Calendar\Concerns\InteractsWithCalendar;
use Guava\Calendar\Contracts\HasCalendar;

abstract class CalendarWidget extends Widget implements HasActions, HasCalendar, HasSchemas
{
    use InteractsWithCalendar;

    protected string $view = 'guava-calendar::widgets.calendar-widget';

    protected int | string | array $columnSpan = 'full';
}
