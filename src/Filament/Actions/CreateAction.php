<?php

namespace Guava\Calendar\Filament\Actions;

use Filament\Schemas\Schema;
use Guava\Calendar\Concerns\CalendarAction;
use Guava\Calendar\Contracts\HasCalendar;

class CreateAction extends \Filament\Actions\CreateAction
{
    use CalendarAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->schema(
                fn (Schema $schema, CreateAction $action, HasCalendar $livewire) => $livewire
                    ->getFormSchemaForModel($schema, $action->getModel())
            )
            ->after(fn (HasCalendar $livewire) => $livewire->refreshRecords())
            ->cancelParentActions()
        ;
    }
}
