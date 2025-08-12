<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\Filament\Actions\DeleteAction;
use Guava\Calendar\Filament\Actions\EditAction;
use Guava\Calendar\Filament\Actions\ViewAction;
use Illuminate\Support\Str;

trait HasDefaultActions
{
    /**
     * Returns a create action configured for the specified model.
     *
     * @param  string  $model  The model class for which you want to make a create action.
     */
    protected function createAction(string $model, ?string $name = null): CreateAction
    {
        if (! $name) {
            $name = Str::of('create')->append(Str::pascal(class_basename($model)))->toString();
        }

        return CreateAction::make($name)
            ->model($model)
        ;
    }

    public function viewAction(): ViewAction
    {
        return ViewAction::make();
    }

    public function editAction(): EditAction
    {
        return EditAction::make();
    }

    public function deleteAction(): DeleteAction
    {
        return DeleteAction::make();
    }
}
