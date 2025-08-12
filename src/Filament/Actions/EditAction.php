<?php

namespace Guava\Calendar\Filament\Actions;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Guava\Calendar\Contracts\HasCalendar;

class EditAction extends \Filament\Actions\EditAction {

    protected function setUp(): void
    {
        parent::setUp();


        $this->model(fn (HasCalendar $livewire) => $livewire->getEventModel());
        $this->record(fn (HasCalendar $livewire) => $livewire->getEventRecord());
        $this->schema([
            TextInput::make('name')
        ]);
//        $this->schema(
//            fn (HasCalendar $livewire) => $livewire
//                ->getInfolistSchemaForModel($livewire->getEventModel())
//                ->getComponents()
//        );
        $this->cancelParentActions();
    }
}
