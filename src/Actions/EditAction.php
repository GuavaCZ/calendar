<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Widgets\CalendarWidget;

class EditAction extends \Filament\Actions\EditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model(fn (CalendarWidget $livewire) => $livewire->getEventModel());
        $this->record(fn (CalendarWidget $livewire) => $livewire->getEventRecord());
        $this->schema(
            fn (CalendarWidget $livewire) => $livewire
                ->getFormSchemaForModel($livewire->getEventModel())
                ->getComponents()
        );
        $this->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords());
        $this->cancelParentActions();
    }
}
