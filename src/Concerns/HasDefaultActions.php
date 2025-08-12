<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Guava\Calendar\Filament\Actions\EditAction;
use Guava\Calendar\Filament\Actions\ViewAction;

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
//
//    public function deleteAction(): Action
//    {
//        return DeleteAction::make();
//    }
}
