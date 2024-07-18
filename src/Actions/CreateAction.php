<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Concerns\HasContext;
use Guava\Calendar\Widgets\CalendarWidget;

class CreateAction extends \Filament\Actions\CreateAction
{
    use HasContext;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('create');
        $this->form(fn (CalendarWidget $livewire, CreateAction $action) => $livewire->getSchema($action->getModel()));
        $this->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords());
        $this->cancelParentActions();
    }
}
