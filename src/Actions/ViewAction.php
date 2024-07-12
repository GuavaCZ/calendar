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

        // When form is not provided or is empty, mountUsing doesn't create and pass a form to the closure.
        // In order to fix this, we need to make filament think we provided a form by simply passing a closure
        // and returning the created form.
        $this->form(fn ($form) => $form);

        // Because we are overriding the mountUsing function, the form is not filled by default anymore.
        // So we fill it manually apart from setting the required model, record and form schema based on the
        // event mounted.
        $this->mountUsing(function (?ComponentContainer $form, $arguments, Action $action, CalendarWidget $livewire) {
            $model = data_get($arguments, 'event.extendedProps.model');
            $key = data_get($arguments, 'event.extendedProps.key');
            $record = $model::query()->find($key);

            $form->schema($livewire->getSchema($model));

            $action->model($model);
            $action->record($record);
            $action->form($livewire->getSchema($model));

            $form->fill($record->getAttributes());
        });
    }
}
