<?php

namespace Guava\Calendar\Actions;

use Filament\Schemas\Schema;
use Guava\Calendar\Widgets\CalendarWidget;

class CreateAction extends \Filament\Actions\CreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->schema(
                fn (CalendarWidget $livewire, CreateAction $action, Schema $schema) => $livewire
                    ->getFormSchemaForModel($schema, $action->getModel())
            )
            ->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords())
            ->cancelParentActions()
        ;
    }
}
