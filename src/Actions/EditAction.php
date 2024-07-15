<?php

namespace Guava\Calendar\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Form;
use Guava\Calendar\Widgets\CalendarWidget;

class EditAction extends \Filament\Actions\EditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('update');
        $this->model(fn(CalendarWidget $livewire) => $livewire->getModel());
        $this->record(fn(CalendarWidget $livewire) => $livewire->getRecord());
        $this->form(fn(CalendarWidget $livewire) => $livewire->getSchemaForModel($livewire->getModel()));
        $this->after(fn(CalendarWidget $livewire) => $livewire->refreshRecords());
    }
}
