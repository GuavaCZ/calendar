<?php

namespace Guava\Calendar\Actions;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Guava\Calendar\Widgets\CalendarWidget;

class TestAction extends CreateAction {

    protected function setUp(): void
    {
        parent::setUp();

        $this->model(
            fn (CalendarWidget $livewire) => 'App\Models\Semester'
        );

        $this->form([TextInput::make('name')]);
//        $this->form(
//            fn (CalendarWidget $livewire) => [
//                TextInput::make('testing')
//            ]
//        );

//        $this->after(
//            fn (FullCalendarWidget $livewire) => $livewire->refreshRecords()
//        );

        $this->cancelParentActions();
    }
}
