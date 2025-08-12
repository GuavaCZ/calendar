<?php

namespace Guava\Calendar\Filament\Actions;

use Filament\Infolists\Components\TextEntry;
use Guava\Calendar\Contracts\HasCalendar;

class ViewAction extends \Filament\Actions\ViewAction {

    protected function setUp(): void
    {
        parent::setUp();


        $this->model(fn (HasCalendar $livewire) => $livewire->getEventModel());
        $this->record(fn (HasCalendar $livewire) => $livewire->getEventRecord());
//        $this->schema(
//            fn (HasCalendar $livewire) => $livewire
//                ->getInfolistSchemaForModel($livewire->getEventModel())
//                ->getComponents()
//        );
        $this->cancelParentActions();
    }
}
