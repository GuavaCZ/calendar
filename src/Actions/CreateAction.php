<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Widgets\CalendarWidget;

class CreateAction extends \Filament\Actions\CreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->form(
                fn (CalendarWidget $livewire, CreateAction $action) => $livewire
                    ->getFormSchemaForModel($action->getModel())
                    ->getComponents()
            )->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords())
            ->cancelParentActions()
        ;
    }
}
