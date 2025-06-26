<?php

namespace Guava\Calendar\Actions;

use Filament\Actions\Action;
use Guava\Calendar\Widgets\CalendarWidget;

class EventClickAction
{
    // TODO: Configure action based on context data
    public static function configure(Action $action): Action
    {
        return $action
            ->model(fn (CalendarWidget $livewire) => $livewire->getEventModel())
            ->record(fn (CalendarWidget $livewire) => $livewire->getEventRecord())
        ;
    }
}
