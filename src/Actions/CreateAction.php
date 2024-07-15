<?php

namespace Guava\Calendar\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Form;
use Guava\Calendar\Widgets\CalendarWidget;

class CreateAction extends \Filament\Actions\CreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('create');
        $this->form(fn(CalendarWidget $livewire, CreateAction $action) => $livewire->getSchemaForModel($action->getModel()));
        $this->after(fn(CalendarWidget $livewire) => $livewire->refreshRecords());
    }
}
