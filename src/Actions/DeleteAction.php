<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Widgets\CalendarWidget;
use Illuminate\Database\Eloquent\Model;

class DeleteAction extends \Filament\Actions\DeleteAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model(fn (CalendarWidget $livewire) => $livewire->getModel());
        $this->record(fn (CalendarWidget $livewire) => $livewire->getRecord());
        $this->after(function (CalendarWidget $livewire) {
            $livewire->record = null;
            $livewire->refreshRecords();
        });
        $this->hidden(static function (?Model $record): bool {
            if (! $record) {
                return false;
            }

            if (! method_exists($record, 'trashed')) {
                return false;
            }

            return $record->trashed();
        });
        $this->cancelParentActions();
    }
}
