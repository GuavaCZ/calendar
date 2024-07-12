<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;

trait HasCustomActions
{
    protected array $cachedCustomActions = [];

    public function bootedHasCustomActions(): void
    {
        $this->cacheCustomActions();
    }

    protected function cacheCustomActions()
    {
        /** @var Action $action */
        foreach ($this->getCustomActions() as $action) {
            $action->livewire($this);

            $this->cacheAction($action);
            $this->cachedCustomActions[] = $action;
        }
    }

    public function getCachedCustomActions(): array
    {
        return $this->cachedCustomActions;
    }

    public function getCustomActions(): array
    {
        return [];
    }
}
