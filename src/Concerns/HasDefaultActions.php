<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Guava\Calendar\Actions\CreateAction;
use Guava\Calendar\Actions\DeleteAction;
use Guava\Calendar\Actions\EditAction;
use Guava\Calendar\Actions\ViewAction;
use Illuminate\Support\Str;

trait HasDefaultActions
{
    /**
     * A create action for the specified model.
     *
     * @param  string  $model  The model class for which you want to make a create action.
     */
    public function createAction(string $model): Action
    {
        $modelSnakeCase = Str::snake(class_basename($model));

        return CreateAction::make("create_$modelSnakeCase")
            ->model($model)
        ;
    }

    public function viewAction(): Action
    {
        return ViewAction::make();
    }

    public function editAction(): Action
    {
        return EditAction::make();
    }

    public function deleteAction(): Action
    {
        return DeleteAction::make();
    }
}
