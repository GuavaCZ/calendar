<?php

namespace Guava\Calendar\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\ViewAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Widgets\Widget;
use Guava\Calendar\Concerns\InteractsWithCalendar;
use Guava\Calendar\Contracts\HasCalendar;

abstract class CalendarWidget extends Widget implements HasCalendar, HasSchemas, HasActions
{
    use InteractsWithCalendar;
    use InteractsWithSchemas;
    use InteractsWithActions;

    protected string $view = 'guava-calendar::widgets.calendar-widget';

    protected int | string | array $columnSpan = 'full';
}
