<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Widgets\CalendarWidget;

class ViewAction extends \Filament\Actions\ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model(fn (CalendarWidget $livewire) => $livewire->getEventModel());
        $this->record(fn (CalendarWidget $livewire) => $livewire->getEventRecord());
        $this->form(fn (CalendarWidget $livewire) => $livewire->getSchema($livewire->getEventModel()));
        $this->cancelParentActions();
    }
}
