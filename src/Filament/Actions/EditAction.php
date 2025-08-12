<?php

namespace Guava\Calendar\Filament\Actions;

use Filament\Schemas\Schema;
use Guava\Calendar\Contracts\HasCalendar;

class EditAction extends \Filament\Actions\EditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model(fn (HasCalendar $livewire) => $livewire->getEventModel());
        $this->record(fn (HasCalendar $livewire) => $livewire->getEventRecord());
//        $this->schema(
//            fn (Schema $schema, HasCalendar $livewire): Schema => $livewire
//                ->getInfolistSchemaForModel($schema, $livewire->getEventModel())
//                ->record($livewire->getEventRecord())
//        );
        $this->cancelParentActions();
    }
}
