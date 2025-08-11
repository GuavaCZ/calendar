<?php

namespace Guava\Calendar\Actions;

use Filament\Schemas\Schema;
use Guava\Calendar\Widgets\CalendarWidget;

class EditAction extends \Filament\Actions\EditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->model(fn (CalendarWidget $livewire) => $livewire->getEventModel())
            ->record(fn (CalendarWidget $livewire) => $livewire->getEventRecord())
//            ->schema(
//                fn (CalendarWidget $livewire, EditAction $action, Schema $schema) => $livewire
//                    ->getFormSchemaForModel($schema, $action->getModel())
//                    ->record($action->getRecord())
//            )
            ->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords())
            ->cancelParentActions()
        ;
    }
}
