<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Guava\Calendar\Actions\DeleteAction;
use Guava\Calendar\Actions\EditAction;
use Guava\Calendar\Actions\ViewAction;

trait HasDefaultActions
{
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
