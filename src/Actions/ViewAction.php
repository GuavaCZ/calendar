<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Widgets\CalendarWidget;

class ViewAction extends \Filament\Actions\ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->schema(
                fn (CalendarWidget $livewire) => $livewire
                    ->getInfolistSchemaForModel($livewire->getEventModel())
                    ->getComponents()
            )
            ->cancelParentActions()
        ;
    }
}
