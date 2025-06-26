<?php

namespace Guava\Calendar\Actions;

use Guava\Calendar\Widgets\CalendarWidget;

class EditAction extends \Filament\Actions\EditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->schema(
                fn (CalendarWidget $livewire, ?string $model) => $livewire
                    ->getFormSchemaForModel($model)
                    ->getComponents()
            )
            ->after(fn (CalendarWidget $livewire) => $livewire->refreshRecords())
            ->cancelParentActions()
        ;
    }
}
