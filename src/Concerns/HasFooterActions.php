<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;

trait HasFooterActions
{
    protected array $cachedFooterActions = [];

    public function bootedHasFooterActions(): void
    {
        $this->cacheFooterActions();
    }

    protected function cacheFooterActions()
    {
        /** @var Action $action */
        foreach ($this->getFooterActions() as $action) {
            $action->livewire($this);

            $this->cacheAction($action);
            $this->cachedFooterActions[] = $action;
        }
    }

    public function getCachedFooterActions(): array
    {
        return $this->cachedFooterActions;
    }

    public function getFooterActions(): array
    {
        return [];
    }
}
