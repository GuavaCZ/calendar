<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Widgets\CalendarWidget;

class CreateAction extends \Filament\Actions\CreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->form(fn (CalendarWidget $livewire, CreateAction $action) => $livewire->getSchema($action->getModel()));
        $this->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords());
        $this->cancelParentActions();
    }
}
