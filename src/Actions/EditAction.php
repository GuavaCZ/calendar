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
        $this
//            ->resolveRecordUsing(fn($key) => dd('asdf', $key))
//            ->mountUsing(fn($arguments, $context) => dd($arguments, $context))
            ->schema(
            fn (CalendarWidget $livewire, $record) => $livewire
                ->getFormSchemaForModel($record)
                ->getComponents()
        );
        $this->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords());
        $this->cancelParentActions();
    }
}
