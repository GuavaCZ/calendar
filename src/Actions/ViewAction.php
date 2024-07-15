<?php

namespace Guava\Calendar\Actions;

use Filament\Actions\Action;
use Filament\Forms\ComponentContainer;
use Guava\Calendar\Widgets\CalendarWidget;

class ViewAction extends \Filament\Actions\ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('view');
        $this->model(fn(CalendarWidget $livewire) => $livewire->getModel());
        $this->record(fn(CalendarWidget $livewire) => $livewire->getRecord());
        $this->form(fn(CalendarWidget $livewire) => $livewire->getSchemaForModel($livewire->getModel()));
    }
}
