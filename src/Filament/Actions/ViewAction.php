<?php

namespace Guava\Calendar\Filament\Actions;

use Filament\Schemas\Schema;
use Guava\Calendar\Contracts\HasCalendar;

class ViewAction extends \Filament\Actions\ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->model(fn (HasCalendar $livewire) => $livewire->getEventModel())
            ->record(fn (HasCalendar $livewire) => $livewire->getEventRecord())
            ->schema(
                fn (ViewAction $action, Schema $schema, HasCalendar $livewire) => $livewire
                    ->getInfolistSchemaForModel($schema, $action->getModel())
                    ->record($livewire->getEventRecord()),
            )
            ->cancelParentActions()
        ;
    }
}
