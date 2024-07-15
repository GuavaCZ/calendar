<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Guava\Calendar\Actions\DeleteAction;
use Guava\Calendar\Actions\EditAction;
use Guava\Calendar\Actions\ViewAction;

trait HasDefaultActions
{
//    protected array $cachedCustomActions = [];
//
//    public function bootedHasCustomActions(): void
//    {
//        $this->cacheCustomActions();
//    }
//
//    protected function cacheCustomActions(): void
//    {
//        /** @var Action $action */
//        foreach ($this->getCustomActions() as $action) {
//            $action->livewire($this);
//
//            $this->cacheAction($action);
//            $this->cachedCustomActions[] = $action;
//        }
//
//    }
//
//    public function getCachedCustomActions(): array
//    {
//        return $this->cachedCustomActions;
//    }
//
//    public function getCustomActions(): array
//    {
//        return [];
//    }

    public function viewAction(): Action
    {
        return ViewAction::make();
    }

    public function editAction(): Action
    {
        return EditAction::make();
    }
}
