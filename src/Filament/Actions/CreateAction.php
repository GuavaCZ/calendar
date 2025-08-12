<?php

namespace Guava\Calendar\Filament\Actions;

use Guava\Calendar\Contracts\HasCalendar;

class CreateAction extends \Filament\Actions\CreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->form(
            fn (HasCalendar $livewire, CreateAction $action) => $livewire
                ->getFormSchemaForModel($action->getModel())
                ->getComponents()
        );
        $this->after(fn (HasCalendar $livewire) => $livewire->refreshRecords());
        $this->cancelParentActions();
    }
}
