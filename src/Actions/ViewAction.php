<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Widgets\CalendarWidget;

class ViewAction extends \Filament\Actions\ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model(fn (CalendarWidget $livewire) => $livewire->getModel());
        $this->record(fn (CalendarWidget $livewire) => $livewire->getRecord());
        $this->form(fn (CalendarWidget $livewire) => $livewire->getSchema($livewire->getModel()));
        $this->cancelParentActions();
    }
}
