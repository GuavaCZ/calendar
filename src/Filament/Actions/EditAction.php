<?php

namespace Guava\Calendar\Filament\Actions;

use Filament\Schemas\Schema;
use Guava\Calendar\Contracts\HasCalendar;

class EditAction extends \Filament\Actions\EditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->model(fn (HasCalendar $livewire) => $livewire->getEventModel())
            ->record(fn (HasCalendar $livewire) => $livewire->getEventRecord())
            ->schema(
                fn (EditAction $action, Schema $schema, HasCalendar $livewire): Schema => $livewire
                    ->getFormSchemaForModel($schema, $action->getModel())
                    ->record($livewire->getEventRecord())
            )->after(fn (HasCalendar $livewire) => $livewire->refreshRecords())
            ->cancelParentActions()
        ;
    }
}
