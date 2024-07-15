<?php

namespace Guava\Calendar\Widgets;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Widgets\Widget;
use Guava\Calendar\Concerns\HandlesEventClick;
use Guava\Calendar\Concerns\HasCalendarView;
use Guava\Calendar\Concerns\HasDayMaxEvents;
use Guava\Calendar\Concerns\HasDefaultActions;
use Guava\Calendar\Concerns\HasEventContent;
use Guava\Calendar\Concerns\HasEvents;
use Guava\Calendar\Concerns\HasFirstDay;
use Guava\Calendar\Concerns\HasFooterActions;
use Guava\Calendar\Concerns\HasHeaderActions;
use Guava\Calendar\Concerns\HasHeading;
use Guava\Calendar\Concerns\HasLocale;
use Guava\Calendar\Concerns\HasMoreLinkContent;
use Guava\Calendar\Concerns\HasOptions;
use Guava\Calendar\Concerns\HasResources;
use Guava\Calendar\Concerns\HasSchema;
use Guava\Calendar\Concerns\InteractsWithRecord;

class CalendarWidget extends Widget implements HasActions, HasForms
{
    use EvaluatesClosures;
    use HandlesEventClick;
    use HasCalendarView;
    use HasDayMaxEvents;
    use HasDefaultActions;
    use HasEventContent;
    use HasEvents;
    use HasFirstDay;
    use HasFooterActions;
    use HasHeaderActions;
    use HasHeading;
    use HasLocale;
    use HasMoreLinkContent;
    use HasOptions;
    use HasResources;
    use HasSchema;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithRecord;

    protected static string $view = 'guava-calendar::widgets.calendar';

    protected int | string | array $columnSpan = 'full';

    public function refreshRecords(): void
    {
        $this->dispatch('calendar--refresh');
    }
}
