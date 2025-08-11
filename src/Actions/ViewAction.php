<?php

namespace Guava\Calendar\Actions;

use Filament\Schemas\Schema;
use Guava\Calendar\Widgets\CalendarWidget;

class ViewAction extends \Filament\Actions\ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->model(fn (CalendarWidget $livewire) => $livewire->getEventModel())
            ->record(fn (CalendarWidget $livewire) => $livewire->getEventRecord())
//            ->schema(
//                fn (CalendarWidget $livewire, ViewAction $action, Schema $schema) => $livewire
//                    ->getInfolistSchemaForModel($schema, $action->getModel())
//                    ->record($action->getRecord())
//            )
            ->cancelParentActions()
        ;
    }
}
