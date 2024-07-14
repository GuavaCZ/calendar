<?php

namespace Guava\Calendar\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Form;
use Guava\Calendar\Widgets\CalendarWidget;

class DeleteAction extends \Filament\Actions\DeleteAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model(fn(CalendarWidget $livewire) => $livewire->getModel());
        $this->record(fn(CalendarWidget $livewire) => $livewire->getRecord());
        $this->after(function(CalendarWidget $livewire) {
            $livewire->record = null;
            $livewire->refreshRecords();
        });
        $this->cancelParentActions();
    }
}
